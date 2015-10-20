<?php

namespace FiveDice\Controller;

use Silex\Application;

class WebController
{
    /**
     * @param Application $app
     * @return string
     */
    public function index(Application $app)
    {
        return $app['twig']->render('index.html.twig', []);
    }

    /**
     * @param Application $app
     * @return string
     */
    public function prototype(Application $app)
    {
        return $app['twig']->render('prototype.html.twig', []);
    }

}
