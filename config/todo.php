<?php

use Symfony\Component\HttpFoundation\Request;

$todo = $app['controllers_factory'];

// Homepage
$todo
    ->get('/', function () use ($app) {
        return $app['twig']->render('index.html.twig', [
            'count' => $app['todo_mapper']->countAll(),
            'todos' => $app['todo_mapper']->findAll(),
        ]);
    })
    ->bind('homepage')
;

// Show one task
$todo
    ->get('/{id}', function ($id) use ($app) {
        if (!$todo = $app['todo_mapper']->find($id)) {
            $app->abort(404, sprintf('Todo #%u does not exist.', $id));
        }

        return $app['twig']->render('todo.html.twig', [ 'todo' => $todo ]);
    })
    ->bind('todo')
    ->assert('id', '\d+')
    //->comment('a comment to explain the feature')
;

// Create a new task
$todo
    ->post('/', function (Request $request) use ($app) {
        $title = $request->request->get('title');
        if (empty($title)) {
            $app->abort(400, 'Missing title to create a new todo.');
        }

        $id = $app['todo_mapper']->create($title);

        return $app->redirect($app['url_generator']->generate('todo', ['id' => $id]));
    })
    ->bind('todo_create')
;

// Close an existing task
$todo
    ->match('/{id}/close', function ($id) use ($app) {
        if (!$todo = $app['todo_mapper']->find($id)) {
            $app->abort(404, sprintf('Todo #%u does not exist.', $id));
        }

        if ($todo['is_done']) {
            $app->abort(404, sprintf('Todo #%u is already done.', $id));
        }

        $app['todo_mapper']->close($id);

        return $app->redirect($app['url_generator']->generate('homepage'));
    })
    ->bind('todo_close')
    ->assert('id', '\d+')
    ->method('POST|PUT|PATCH')
;

// Delete an existing task
$todo
    ->match('/{id}/delete', function ($id) use ($app) {
        if (!$todo = $app['todo_mapper']->find($id)) {
            $app->abort(404, sprintf('Todo #%u does not exist.', $id));
        }

        $app['todo_mapper']->delete($id);

        return $app->redirect($app['url_generator']->generate('homepage'));
    })
    ->bind('todo_delete')
    ->assert('id', '\d+')
    ->method('POST|DELETE')
;

return $todo;
