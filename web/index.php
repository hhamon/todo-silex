<?php

require_once __DIR__.'/../vendor/autoload.php';

use Domain\TodoMapper;
use Silex\Application;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Symfony\Component\HttpFoundation\Request;

$app = new Application([ 'debug' => true ]);
$app->register(new UrlGeneratorServiceProvider());
$app->register(new TwigServiceProvider(), [
    'twig.path'            => __DIR__.'/../views',
    'twig.options'         => [
        'cache'            => __DIR__.'/../cache/twig',
        'debug'            => $app['debug'],
        'strict_variables' => $app['debug'],
        'auto_reload'      => $app['debug'],
    ],
]);
$app->register(new DoctrineServiceProvider(), [
    'db.options' => [
        'driver'    => 'pdo_mysql',
        'host'      => 'localhost',
        'dbname'    => 'training_todo',
        'user'      => 'root',
        'password'  => '',
        'charset'   => 'utf8',
    ],
]);

// Homepage
$app
    ->get('/', function () use ($app) {
        $mapper = new TodoMapper($app['db']);

        return $app['twig']->render('index.html.twig', [
            'count' => $mapper->countAll(),
            'todos' => $mapper->findAll(),
        ]);
    })
    ->bind('homepage')
;

// Show one task
$app
    ->get('/todo/{id}', function ($id) use ($app) {
        $mapper = new TodoMapper($app['db']);

        if (!$todo = $mapper->find($id)) {
            $app->abort(404, sprintf('Todo #%u does not exist.', $id));
        }

        return $app['twig']->render('todo.html.twig', [ 'todo' => $todo ]);
    })
    ->bind('todo')
    ->assert('id', '\d+')
    //->comment('a comment to explain the feature')
;

// Create a new task
$app
    ->post('/todo', function (Request $request) use ($app) {
        $title = $request->request->get('title');
        if (empty($title)) {
            $app->abort(400, 'Missing title to create a new todo.');
        }

        $mapper = new TodoMapper($app['db']);
        $id = $mapper->create($title);

        return $app->redirect($app['url_generator']->generate('todo', ['id' => $id]));
    })
    ->bind('todo_create')
;

// Close an existing task
$app
    ->match('/todo/{id}/close', function ($id) use ($app) {
        $mapper = new TodoMapper($app['db']);
        if (!$todo = $mapper->find($id)) {
            $app->abort(404, sprintf('Todo #%u does not exist.', $id));
        }

        if ($todo['is_done']) {
            $app->abort(404, sprintf('Todo #%u is already done.', $id));
        }

        $mapper->close($id);

        return $app->redirect($app['url_generator']->generate('homepage'));
    })
    ->bind('todo_close')
    ->assert('id', '\d+')
    ->method('POST|PUT|PATCH')
;

// Delete an existing task
$app
    ->match('/todo/{id}/delete', function ($id) use ($app) {
        $mapper = new TodoMapper($app['db']);
        if (!$todo = $mapper->find($id)) {
            $app->abort(404, sprintf('Todo #%u does not exist.', $id));
        }

        $mapper->delete($id);

        return $app->redirect($app['url_generator']->generate('homepage'));
    })
    ->bind('todo_delete')
    ->assert('id', '\d+')
    ->method('POST|DELETE')
;

// Run the application
$app->run(Request::createFromGlobals());
