<?php

namespace Todo\Provider;

use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;

class TodoControllerProvider implements ControllerProviderInterface
{
    /**
     * Returns routes to connect to the given application.
     *
     * @param Application $app An Application instance
     *
     * @return ControllerCollection A ControllerCollection instance
     */
    public function connect(Application $app)
    {
       $todo = $app['controllers_factory'];

        // Homepage
        $todo
            ->get('/', 'todo_controller:indexAction')
            ->bind('homepage')
        ;

        // Show one task
        $todo
            ->get('/{id}', 'todo_controller:todoAction')
            ->bind('todo')
            ->assert('id', '\d+')
            //->comment('a comment to explain the feature')
        ;

        // Create a new task
        $todo
            ->post('/', 'todo_controller:createAction')
            ->bind('todo_create')
        ;

        // Close an existing task
        $todo
            ->match('/{id}/close', 'todo_controller:closeAction')
            ->bind('todo_close')
            ->assert('id', '\d+')
            ->method('POST|PUT|PATCH')
        ;

        // Delete an existing task
        $todo
            ->match('/{id}/delete', 'todo_controller:deleteAction')
            ->bind('todo_delete')
            ->assert('id', '\d+')
            ->method('POST|DELETE')
        ;

        return $todo;
    }
} 
