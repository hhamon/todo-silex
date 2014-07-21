<?php

require_once __DIR__.'/../vendor/autoload.php';

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$app = new Application([ 'debug' => true ]);

// Homepage
$app
    ->get('/', function () {
        
    })
    ->bind('homepage')
;

// Show one task
$app
    ->get('/todo/{id}', function ($id) {
        
    })
    ->bind('todo')
    ->assert('id', '\d+')
    //->comment('a comment to explain the feature')
;


// Create a new task
$app
    ->post('/todo', function (Request $request) {

    })
    ->bind('todo_create')
;

// Close an existing task
$app
    ->match('/todo/{id}/close', function ($id) {

    })
    ->bind('todo_close')
    ->assert('id', '\d+')
    ->method('POST|PUT|PATCH')
;

// Delete an existing task
$app
    ->match('/todo/{id}/delete', function ($id) {

    })
    ->bind('todo_delete')
    ->assert('id', '\d+')
    ->method('POST|DELETE')
;

// Run the application
$app->run(Request::createFromGlobals());
