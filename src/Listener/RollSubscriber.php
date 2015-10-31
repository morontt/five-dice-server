<?php

namespace FiveDice\Listener;

use Finite\Event\FiniteEvents;
use Finite\Event\TransitionEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class RollSubscriber implements EventSubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            FiniteEvents::POST_TRANSITION . '.roll_1' => 'rolling',
            FiniteEvents::POST_TRANSITION . '.roll_2' => 'rolling',
            FiniteEvents::POST_TRANSITION . '.roll_3' => 'rolling',
        ];
    }

    /**
     * @param TransitionEvent $e
     */
    public function rolling(TransitionEvent $e)
    {
        $gameState = $e->getStateMachine()->getObject();

        for ($i = 1; $i < 7; $i++) {
            $field = 'dice' . $i;
            $gameState->$field = 1 + (mt_rand() % 6);
        }
    }
}
