<?php
// Autoload managed by composer.
require 'vendor/autoload.php';

use Vrap\TheBlenderCommunity\Repositories;
use Vrap\TheBlenderCommunity;

$configuration = TheBlenderCommunity\Configurator::getInstance()->load(
    join(DIRECTORY_SEPARATOR, array(__DIR__, 'config.ini'))
);

// Load Slim framework
$app = new \Slim\Slim();

/**
 * Recipe related methods.
 */
$app->get('/recipes/:ruid', function($ruid) {
    $recipe = Repositories\Recipes::retrieveById($ruid);

    if ($recipe) {
        $response = array('status' => true, 'data' => $recipe);
    }
    else {
        $response = array('status' => false);
    }

    echo json_encode($response);
});

$app->get('/recipes', function() {
    $recipes = Repositories\Recipes::retrieveAll();

    if ($recipes) {
        $response = array('status' => true, 'data' => $recipes);
    }
    else {
        $response = array('status' => false);
    }

    echo json_encode($response);
});

$app->delete('/recipes/:ruid', function($ruid) {
    if (Repositories\Recipes::remove($ruid)) {
        $response = array('status' => true, 'data' => array());
    }
    else {
        $response = array('status' => false);
    }

    echo json_encode($response);
});

/**
 * User related methods.
 */
$app->get('/users/:uid', function($uid) {
    $user = Repositories\Users::retrieveById($uid);

    if ($user) {
        $response = array('status' => true, 'data' => $user);
    }
    else {
        $response = array('status' => false);
    }

    echo json_encode($response);
});

$app->get('/users', function() {
    $users = Repositories\Users::retrieveAll();

    if ($users) {
        $response = array('status' => true, 'data' => $users);
    }
    else {
        $response = array('status' => false);
    }

    echo json_encode($response);
});

$app->get('/logout', function() {
    $response = array('status' => false, 'data' => array());

    echo json_encode($response);
});

/**
 * Other related methods.
 */
$app->get('/infos', function() {
    $stats = array();

    echo json_encode($stats);
});

/**
 * Start Slim application.
 */
$app->run();