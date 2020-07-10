<?php

$app->get('/user/{id}', function($params) {
    $ctrl = new App\Controller\UserController();

    return $ctrl->findOne($params['param1']);
});

$app->get('/user', function($params) {
    $ctrl = new App\Controller\UserController();

    return array("user" => $ctrl->findAll($params));
    //return $ctrl->findAll($params);
});

$app->post('/user', function($params) {
    $ctrl = new App\Controller\UserController($params);
    $ctrl->save();

    return $ctrl->model;
});

$app->put('/user', function($params) {
    $ctrl = new App\Controller\UserController($params);
    //$ctrl->find();
    $ctrl->save();

    return $ctrl->model;
});

$app->delete('/user', function($params) {
    $ctrl = new App\Controller\UserController($params);
    $ctrl->delete();

    return $ctrl->model;
});
