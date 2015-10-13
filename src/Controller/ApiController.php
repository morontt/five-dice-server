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
        $app['fd_database']->createPlayerScore($gameState->id, $app['fd_player']->id);

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

    /**
     * @param Application $app
     * @param string $hash
     * @return JsonResponse
     */
    public function join(Application $app, $hash)
    {
        $gameStates = $app['fd_database']->getPendingGameStateWithPlayers($hash);
        $playerId = $app['fd_player']->id;

        $result = ['status' => 'ok'];

        if (count($gameStates)) {
            foreach ($gameStates as $state) {
                if ((int)$state['player_id'] === $playerId) {
                    $result = ['status' => 'error', 'message' => 'duplicate user'];
                    break;
                }
            }
        } else {
            $result = ['status' => 'error', 'message' => 'not found or not pending'];
        }

        if ($result['status'] != 'error') {
            $app['fd_database']->createPlayerScore((int)$gameStates[0]['id'], $playerId);
        }

        return new JsonResponse($result);
    }
}
