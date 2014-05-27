<?php
// Autoload managed by composer.
require 'vendor/autoload.php';

use Vrap\TheBlenderCommunity\Repositories;

// Load Slim framework
$app = new \Slim\Slim();

/**
 * Recipe related methods.
 */
$app->get('/recipe/:ruid', function($ruid) {
    $recipe = Recipes::retrieveById($ruid);

    if ($recipe) {
        $response = array('status' => true, 'data' => $recipe);
    }
    else {
        $response = array('status' => false);
    }

    echo json_encode($response);
});

$app->delete('/recipe/:ruid', function($ruid) {
    try {
        Recipes::deleteById($ruid);

        $response = array('status' => true, 'data' => array());
    }
    catch (Exception $e) {
        $response = array('status' => false, 'data' => $e);
    }

    echo json_encode($response);
});

/**
 * User related methods.
 */
$app->get('/user/:uid', function($uid) {
    $user = Users::retrieveById($uid);

    if ($user) {
        $response = array('status' => true, 'data' => $user);
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

// Start Slim application.
$app->run();