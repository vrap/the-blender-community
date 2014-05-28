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

// Add CORS autorisations
$app->response->header('Access-Control-Allow-Origin', '*'); 
$app->response->header('Access-Control-Allow-Headers', 'Content-Type, X-Requested-With, X-authentication, X-client');
$app->response->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');

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

$app->post('/register', function() use($app) {
    $response = array('status' => false, 'data' => array());

    $username = $app->request()->post('username');
    $email    = $app->request()->post('email');
    $password = $app->request()->post('password');

    if (!empty($username) && !empty($email) && !empty($password)) {
        // Validate email
        $pattern = '/^[a-zA-Z0-9+&*-]+(?:\.[a-zA-Z0-9_+&*-]+)*@(?:[a-zA-Z0-9-]+\.)+[a-zA-Z]{2,7}$/';
        $validateEmail = preg_match($pattern, $email);

        if (!$validateEmail) {
            $response['data']['error'] = 'ERR_EMAIL_NOT_VALID';
        }
        elseif (Repositories\Users::retrieveByEmail($email)) {
            $response['data']['error'] = 'ERR_EMAIL_ALREADY_EXIST';
        }
        elseif (Repositories\Users::retrieveByUsername($username)) {
            $response['data']['error'] = 'ERR_USERNAME_ALREADY_EXIST';
        }

        if (!isset($response['data']['error'])) {
            if (Repositories\Users::save($username, $email, $password)) {
                $response['status'] = true;
            }
        }
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