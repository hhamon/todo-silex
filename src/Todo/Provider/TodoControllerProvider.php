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

        $todo->get('/', 'todo_controller:indexAction')->bind('homepage');
        $todo->get('/{id}', 'todo_controller:todoAction')->bind('todo')->assert('id', '\d+');
        $todo->post('/', 'todo_controller:createAction')->bind('todo_create');
        $todo->match('/{id}/close', 'todo_controller:closeAction')->bind('todo_close')->assert('id', '\d+')->method('POST|PUT|PATCH');
        $todo->match('/{id}/delete', 'todo_controller:deleteAction')->bind('todo_delete')->assert('id', '\d+')->method('POST|DELETE');

        return $todo;
    }
} 
