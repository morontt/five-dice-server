<?php

namespace Controller;

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
        $hash = base_convert((int)(microtime(true) * 1000), 10, 36);

        return new JsonResponse(['status' => 'ok', 'hash' => $hash]);
    }
}
