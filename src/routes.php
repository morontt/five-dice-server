<?php

use Symfony\Component\HttpFoundation\Response;

/**
 * @var \Silex\Application $app
 */
$app->get('/', 'FiveDice\\Controller\\WebController::index')
    ->bind('homepage');

$app->post('/create', 'FiveDice\\Controller\\ApiController::createGame')
    ->before($app['fd_player.middleware'])
    ->bind('create');

$app->get('/pending', 'FiveDice\\Controller\\ApiController::pendingGames')
    ->before($app['fd_player.middleware'])
    ->bind('pending');

$app->post('/join/{hash}', 'FiveDice\\Controller\\ApiController::join')
    ->assert('hash', '[a-z0-9]{8}')
    ->before($app['fd_player.middleware'])
    ->bind('join');

$app->get('/state/{hash}', 'FiveDice\\Controller\\ApiController::getState')
    ->assert('hash', '[a-z0-9]{8}')
    ->before($app['fd_player.middleware'])
    ->bind('get_state');

$app->post('/state/{hash}', 'FiveDice\\Controller\\ApiController::postState')
    ->assert('hash', '[a-z0-9]{8}')
    ->before($app['fd_player.middleware'])
    ->bind('post_state');

$app->error(function (\Exception $e, $code) use ($app) {
    if ($app['debug']) {
        return null;
    }

    // 404.html, or 40x.html, or 4xx.html, or error.html
    $templates = [
        'errors/' . $code . '.html.twig',
        'errors/' . substr($code, 0, 2) . 'x.html.twig',
        'errors/' . substr($code, 0, 1) . 'xx.html.twig',
        'errors/default.html.twig',
    ];

    return new Response($app['twig']->resolveTemplate($templates)->render(['code' => $code]), $code);
});
