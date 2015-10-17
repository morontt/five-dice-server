<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkContext;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends MinkContext implements Context, SnippetAcceptingContext
{
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * @param PyStringNode $string
     *
     *  @Then json response should be:
     */
    public function jsonResponseShouldBe(PyStringNode $string)
    {
        $expectedResponse = json_decode($string->getRaw(), true);

        $this->assert($this->getClientJSON() == $expectedResponse, 'wrong JSON response');
    }

    /**
     * @param string $method
     * @param string $url
     * @param string $player
     *
     * @Given call :method :url with player :player
     */
    public function callWithPlayer($method, $url, $player)
    {
        $startUrl = rtrim($this->getMinkParameter('base_url'), '/');
        $client = $this
            ->getSession()
            ->getDriver()
            ->getClient();

        $client->setHeader('FD-PLAYER-ID', $player)
            ->request($method, $startUrl . $url);

        $client->removeHeader('FD-PLAYER-ID');
    }

    /**
     * @param string $key
     *
     * @Then json response should contain key :key
     */
    public function jsonResponseShouldContainKey($key)
    {
        $clientResponse = $this->getClientJSON();

        $this->assert(isset($clientResponse[$key]), sprintf('JSON response not contain key "%s"', $key));
    }

    /**
     * @param string $player
     * @throws Exception
     *
     * @When player :player join last hash
     */
    public function playerJoinLastHash($player)
    {
        $clientResponse = $this->getClientJSON();

        if (!isset($clientResponse['games']) || !count($clientResponse['games'])) {
            throw new \Exception('hash not found');
        }

        $countGames = count($clientResponse['games']);

        $hash = $clientResponse['games'][$countGames - 1]['hash'];
        $this->callWithPlayer('POST', '/join/' . $hash, $player);
    }

    /**
     * @return array
     */
    protected function getClientJSON()
    {
        return json_decode($this->getSession()->getPage()->getContent(), true);
    }

    /**
     * @Given player :player request state last hash
     */
    public function playerRequestStateLastHash($player)
    {
        $this->callWithPlayer('GET', '/pending', $player);

        $clientResponse = $this->getClientJSON();

        if (!isset($clientResponse['games']) || !count($clientResponse['games'])) {
            throw new \Exception('hash not found');
        }

        $countGames = count($clientResponse['games']);

        $hash = $clientResponse['games'][$countGames - 1]['hash'];
        $this->callWithPlayer('GET', '/state/' . $hash, $player);
    }

    /**
     * @param bool $condition
     * @param string $message
     * @throws \Exception
     */
    protected function assert($condition, $message)
    {
        if ($condition) {
            return;
        }

        throw new \Exception($message);
    }
}
