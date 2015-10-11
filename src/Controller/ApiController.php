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

    /**
     * @param Application $app
     * @return JsonResponse
     */
    public function pendingGames(Application $app)
    {
        $games = $app['fd_database']->getPendingGames();

        return new JsonResponse([
            'status' => 'ok',
            'games' => $games,
            'content_hash' => hash('crc32b', serialize($games)),
        ]);
    }
}
