<?php

use FiveDice\Database\Migrator;
use FiveDice\Database\Repository;
use Silex\Application;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Symfony\Component\HttpFoundation\Request;

$app = new Application();
$app->register(new UrlGeneratorServiceProvider());
$app->register(new TwigServiceProvider());
$app->register(new DoctrineServiceProvider(), [
    'db.options' => [
        'driver' => 'pdo_sqlite',
        'path' => __DIR__ . '/../database/game.db3',
    ],
]);

$app['fd_database'] = $app->share(function ($app) {
    return new Repository($app['db']);
});
$app['fd_database.migrator'] = function ($app) {
    return new Migrator($app['db']);
};

$app['fd_player'] = null;
$app['fd_player.middleware'] = $app->protect(function (Request $request, Application $app) {
    $player = $request->headers->get('FD-PLAYER-ID', null);

    if ($player === null && $app['debug']) {
        $player = '__debug__';
    }

    if ($player) {
        $app['fd_player'] = $app['fd_database']->getPlayer($player);
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
