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
$app->get(
    '/recipes/:ruid',
    function($ruid) {
        $recipe = Repositories\Recipes::retrieveById($ruid);

        if ($recipe) {
            // Retrieve steps of the recipe
            $steps = Repositories\RecipeSteps::retrieveByRecipe($recipe);

            // If steps exists, add params of each steps
            if ($steps) {
                foreach ($steps as $key => $step) {
                    $params = Repositories\RecipeStepValues::retrieveByRecipeStep($step);

                    $steps[$key]['params'] = $params;
                }
            }
            else {
                $steps = array();
            }

            // Add steps to the recipe and return the response
            $recipe['steps'] = $steps;

            $response = array('status' => true, 'data' => $recipe);
        }
        else {
            $response = array('status' => false);
        }

        echo json_encode($response);
    }
);

$app->get(
    '/recipes',
    function() {
        $recipes = Repositories\Recipes::retrieveAll();

        if ($recipes) {
            $response = array('status' => true, 'data' => $recipes);
        }
        else {
            $response = array('status' => false);
        }

        echo json_encode($response);
    }
);

$app->delete(
    '/recipes/:ruid',
    function($ruid) {
        if (Repositories\Recipes::remove($ruid)) {
            $response = array('status' => true, 'data' => array());
        }
        else {
            $response = array('status' => false);
        }

        echo json_encode($response);
    }
);

/**
 * User related methods.
 */
$app->get(
    '/users/:uid',
    function($uid) {
        $user = Repositories\Users::retrieveById($uid);

        if ($user) {
            $response = array('status' => true, 'data' => $user);
        }
        else {
            $response = array('status' => false);
        }

        echo json_encode($response);
    }
);

$app->get(
    '/users',
    function() {
        $users = Repositories\Users::retrieveAll();

        if ($users) {
            $response = array('status' => true, 'data' => $users);
        }
        else {
            $response = array('status' => false);
        }

        echo json_encode($response);
    }
);

$app->get(
    '/register',
    function() use($app) {
        $config = \Vrap\TheBlenderCommunity\Configurator::getInstance();
        $params = $config->get('params', false);
        $response = array('status' => false, 'data' => array());

        if ($params && isset($params['private']) && (bool)$params['private'] === true) {
            $response['data']['error'] = 'ERR_PRIVATE_COMMUNITY';
        }
        else {
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
        }

        echo json_encode($response);
    }
);

$app->get(
    '/logout',
    function() {
        $response = array('status' => false, 'data' => array());

        echo json_encode($response);
    }
);

/**
 * Other related methods.
 */
$app->get(
    '/infos',
    function() {
        $infos = array();
        $config = \Vrap\TheBlenderCommunity\Configurator::getInstance();

        $infos['general'] = $config->get('community');
        $infos['stats'] = array(
            'users' => Repositories\Users::count(),
            'recipes' => Repositories\Recipes::count()
        );

        echo json_encode($infos);
    }
);

/**
 * Start Slim application.
 */
$app->run();