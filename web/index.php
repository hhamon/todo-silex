<?php

require_once __DIR__.'/../vendor/autoload.php';

use Flint\Provider\TackerServiceProvider;
use Flint\Provider\RoutingServiceProvider;
use Todo\Provider\TodoServiceProvider;
use Todo\Provider\TodoControllerProvider;
use Silex\Application;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\WebProfilerServiceProvider;
use Symfony\Component\HttpFoundation\Request;

$app = new Application([ 'debug' => true ]);
$app->register(new TodoServiceProvider());
$app->register(new ServiceControllerServiceProvider());
$app->register(new UrlGeneratorServiceProvider());
$app->register(new TackerServiceProvider(), [
    'root_dir' => __DIR__.'/..',
]);
$app->register(new RoutingServiceProvider, [
    'routing.resource' => $app['root_dir'].'/config/routing.yml',
    'routing.options'  => [
        'cache_dir' => $app['root_dir'].'/cache',
    ],
]);
$app->register(new TwigServiceProvider(), [
    'twig.path'            => $app['root_dir'].'/views',
    'twig.options'         => [
        'cache'            => $app['root_dir'].'/cache/twig',
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

if ($app['debug']) {
    $app->register(new WebProfilerServiceProvider(), [
        'profiler.cache_dir' => $app['root_dir'].'/cache/profiler',
    ]);
}

// Register routes
//$app->mount('/todo', new TodoControllerProvider());

// Run the application
$app->run(Request::createFromGlobals());
