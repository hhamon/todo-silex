<?php

require_once __DIR__.'/../vendor/autoload.php';

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$app = new Application([ 'debug' => true ]);
$app
    ->match('/{name}', function (Request $request) {
        $name = $request->attributes->get('name');

        $response = new Response("Hello $name!");
        $response->headers->set('X-Foo', 'bar');
        $response->headers->set('X-UA', $request->headers->get('User-Agent'));

        return $response;
    })
    ->value('name', 'world')
    ->method('GET')
    ->assert('name', '^[a-z]+$')
;
$app->run(Request::createFromGlobals());
