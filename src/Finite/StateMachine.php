<?php

namespace FiveDice\Finite;

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
}
