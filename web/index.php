<?php

require_once __DIR__.'/../vendor/autoload.php';

use Todo\Provider\TodoServiceProvider;
use Todo\Provider\TodoControllerProvider;
use Silex\Application;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Symfony\Component\HttpFoundation\Request;

$app = new Application([ 'debug' => true ]);
$app->register(new TodoServiceProvider());
$app->register(new ServiceControllerServiceProvider());
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

// Register routes
$app->mount('/todo', new TodoControllerProvider());

// Run the application
$app->run(Request::createFromGlobals());
