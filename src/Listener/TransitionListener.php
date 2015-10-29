<?php

namespace FiveDice\Listener;

use Finite\Event\TransitionEvent;

class TransitionListener
{
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
    }
}
