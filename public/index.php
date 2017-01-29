<?php

require '../Core/App.php';

$app = \Core\App::getInstance();
$router = new \Routers\RESTRouter();
$app->setRouter($router);

$router->add( 'get','news', '\Controllers\NewsController::all');
$router->add( 'get','news/{id}', '\Controllers\NewsController::find');
$router->add( 'post','news', '\Controllers\NewsController::add');
$router->add( 'put','news/{id}', '\Controllers\NewsController::update');
$router->add( 'delete','news/{id}', '\Controllers\NewsController::delete');

$app->run();

