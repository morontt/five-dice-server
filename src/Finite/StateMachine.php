<?php

namespace FiveDice\Finite;

use Finite\Event\FiniteEvents;
use Finite\StateMachine\StateMachine as Machine;
use Finite\State\State;
use Finite\State\StateInterface;
use FiveDice\Model\GameState;

class StateMachine
{
    const STATE_PENDING = 'pending';
    const STATE_REPLACEMENT_PLAYER = 'replace';
    const STATE_ROLLING_1 = 'rolling_1';
    const STATE_ROLLING_2 = 'rolling_2';
    const STATE_ROLLING_3 = 'rolling_3';
    const STATE_SCORE = 'score';
    const STATE_CLOSED = 'closed';

    /**
     * @var Machine
     */
    protected $sm;


    function __construct()
    {
        $this->sm = new Machine();

        $this->sm->addState(new State(self::STATE_PENDING, StateInterface::TYPE_INITIAL));
        $this->sm->addState(self::STATE_REPLACEMENT_PLAYER);
        $this->sm->addState(self::STATE_ROLLING_1);
        $this->sm->addState(self::STATE_ROLLING_2);
        $this->sm->addState(self::STATE_ROLLING_3);
        $this->sm->addState(new State(self::STATE_CLOSED, StateInterface::TYPE_FINAL));

        $this->sm->addTransition('start', self::STATE_PENDING, self::STATE_REPLACEMENT_PLAYER);
        $this->sm->addTransition('end', self::STATE_REPLACEMENT_PLAYER, self::STATE_CLOSED);
        $this->sm->addTransition('roll_1', self::STATE_REPLACEMENT_PLAYER, self::STATE_ROLLING_1);
        $this->sm->addTransition('roll_2', self::STATE_REPLACEMENT_PLAYER, self::STATE_ROLLING_2);
        $this->sm->addTransition('roll_3', self::STATE_REPLACEMENT_PLAYER, self::STATE_ROLLING_3);
        $this->sm->addTransition('next', self::STATE_SCORE, self::STATE_REPLACEMENT_PLAYER);
        $this->sm->addTransition('score_1', self::STATE_ROLLING_1, self::STATE_SCORE);
        $this->sm->addTransition('score_2', self::STATE_ROLLING_2, self::STATE_SCORE);
        $this->sm->addTransition('score_3', self::STATE_ROLLING_3, self::STATE_SCORE);

        $d = $this->sm->getDispatcher();
        $d->addListener(FiniteEvents::POST_TRANSITION . '.start', 'FiveDice\Listener\TransitionListener::replacePlayer');
        $d->addListener(FiniteEvents::POST_TRANSITION . '.next', 'FiveDice\Listener\TransitionListener::replacePlayer');
    }

    /**
     * @param GameState $obj
     * @throws \Finite\Exception\ObjectException
     */
    public function init(GameState $obj)
    {
        $this->sm->setObject($obj);
        $this->sm->initialize();
    }

    /**
     * @return StateInterface
     */
    public function getCurrentState()
    {
        return $this->sm->getCurrentState();
    }

    /**
     * @{inheritDoc}
     */
    public function apply($transitionName)
    {
        return $this->sm->apply($transitionName);
    }
}
