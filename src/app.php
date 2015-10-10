<?php

use Silex\Application;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Symfony\Component\HttpFoundation\Request;

$app = new Application();
$app->register(new UrlGeneratorServiceProvider());
$app->register(new TwigServiceProvider());

$app['fd_player'] = null;
$app['fd_player.middleware'] = $app->protect(function (Request $request, Application $app) {
    $player = $request->headers->get('FD-PLAYER-ID', null);
    if ($player) {
        $app['fd_player'] = [
            'player' => $player,
        ];
    } else {
        return $app->json(
            [
                'status' => 'error',
                'message' => 'FD-PLAYER-ID header is required',
            ],
            401
        );
    }
});

return $app;
