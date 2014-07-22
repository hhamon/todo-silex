<?php

namespace Todo\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Todo\Controller\TodoController;
use Todo\Domain\TodoMapper;

class TodoServiceProvider implements ServiceProviderInterface
{
    /**
     * Registers services on the given app.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Application $app An Application instance
     */
    public function register(Application $app)
    {
        // Define a global parameter
        $app['todo.max_per_page'] = 10;

        // Register a new service
        $app['todo_mapper'] = $app->share(function (Application $app) {
            return new TodoMapper($app['db']);
        });

        // Register a controller as a service
        $app['todo_controller'] = $app->share(function (Application $app) {
            return new TodoController($app['twig'], $app['todo_mapper'], $app['url_generator']);
        });
    }

    /**
     * Bootstraps the application.
     *
     * This method is called after all services are registered
     * and should be used for "dynamic" configuration (whenever
     * a service must be requested).
     */
    public function boot(Application $app)
    {

    }
}
