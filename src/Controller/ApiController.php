<?php

namespace Controller;

use Model\GameState;
use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiController
{
    /**
     * @param Application $app
     * @return JsonResponse
     */
    public function createGame(Application $app)
    {
        $gameState = new GameState();

        $app['fd_database']->createGame($gameState);
        $app['fd_database']->createPlayerScore($gameState, $app['fd_player']);

        return new JsonResponse(['status' => 'ok', 'hash' => $gameState->hash]);
    }
}
