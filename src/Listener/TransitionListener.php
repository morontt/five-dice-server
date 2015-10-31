<?php

namespace FiveDice\Listener;

use Finite\Event\TransitionEvent;
use FiveDice\Model\GameState;

class TransitionListener
{
    /**
     * @param TransitionEvent $e
     */
    public static function activate(TransitionEvent $e)
    {
        $gameState = $e->getStateMachine()->getObject();
        $gameState->status = GameState::STATUS_ACTIVE;
    }

    /**
     * @param TransitionEvent $e
     */
    public static function replacePlayer(TransitionEvent $e)
    {
        $gameState = $e->getStateMachine()->getObject();

        if ($gameState->stepPlayer) {
            $idx = 1 + array_search($gameState->stepPlayer, $gameState->players);
            if ($idx == count($gameState->players)) {
                $idx = 0;
            }
        } else {
            $idx = mt_rand() % count($gameState->players);
        }

        $gameState->stepPlayer = $gameState->players[$idx];
        $e->getStateMachine()->apply('roll_1');
    }

    /**
     * @param TransitionEvent $e
     */
    public static function checkState(TransitionEvent $e)
    {
        $sm = $e->getStateMachine();
        $currentState = $sm->getCurrentState()->getName();

        if (strpos($currentState, 'rolling_') === 0) {
            $gameState = $sm->getObject();
            $gameState->countRolling = (int)str_replace('rolling_', '', $currentState);
        }
    }
}
