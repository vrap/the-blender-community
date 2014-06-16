<?php
// Autoload managed by composer.
require 'vendor/autoload.php';

use Vrap\TheBlenderCommunity\Repositories;
use Vrap\TheBlenderCommunity\Middlewares;
use Vrap\TheBlenderCommunity;

$configuration = TheBlenderCommunity\Configurator::getInstance()->load(
    join(DIRECTORY_SEPARATOR, array(__DIR__, 'config.ini'))
);

// Load Slim framework
$app = new \Slim\Slim();

// Add Slim SessionCookie middleware
$app->add(new \Slim\Middleware\SessionCookie());

// Instanciate Authenticator
Middlewares\Authenticator::getInstance()->setApp($app);

// Add CORS autorisations
$app->response->header('Access-Control-Allow-Origin', '*'); 
$app->response->header('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept, Authorization');
$app->response->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');

/**
 * Truc chelou recupéré sur le net pour resoudre problème CORS avec OPTION
 */
$app->map('/:x+', function($x) {
    http_response_code(200);
})->via('OPTIONS');

/**
 * Recipe related methods.
 */
$app->get(
    '/recipes/:ruid',
    function() use($app) {
        $auth = Middlewares\Authenticator::getInstance();

        if ($auth->isAuth() === false) {
            $app->halt(403);
        }
    },
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
    function() use($app) {
        $auth = Middlewares\Authenticator::getInstance();

        // if ($auth->isAuth() === false) {
        //     $app->halt(403);
        // }
        // 
    },
    function() {
        $recipes = Repositories\Recipes::retrieveAllWithSteps();
        if ($recipes) {
            $response = array('status' => true, 'data' => $recipes);
        }
        else {
            $response = array('status' => false);
        }

        echo json_encode($response);
    }
);

$app->post(
    '/recipes',
    function() use($app) {
        $auth = Middlewares\Authenticator::getInstance();

        // if ($auth->isAuth() === false) {
        //     $app->halt(403);
        // }
        // 
    },
    function() use($app){

        $response = array('status' => false, 'data' => array());

        $recipe = json_decode($app->request()->post('data'));
        $result = Repositories\Recipes::save($recipe);

        if($result){
            $response = array('status' => true, 'data' => array());
        }
        
        echo json_encode($response);


    }
);

$app->delete(
    '/recipes/:ruid',
    function() use($app) {
        $auth = Middlewares\Authenticator::getInstance();

        if ($auth->isAuth() === false) {
            $app->halt(403);
        }
    },
    function($ruid) {
        $auth = Middlewares\Authenticator::getInstance();
        $recipe = Repositories\Recipes::retrieveById($ruid);
        $response = array('status' => false, 'data' => array());

        if ($recipe) {
            if (!$auth->getUser() || ($recipe['author'] !== $auth->getUser()['uuid'])) {
                $response['data'] = 'ERR_UNAUTHORIZED_ACTION';
            }
            else {
                if (Repositories\Recipes::remove($ruid)) {
                    $response['status'] = true;
                }
            }
        }
        else {
            $response['status'] = false;
            $response['data'] = 'ERR_RECIPE_NOT_FOUND';
        }

        echo json_encode($response);
    }
);

/**
 * User related methods.
 */
$app->get(
    '/users/:uid',
    function() use($app) {
        $auth = Middlewares\Authenticator::getInstance();

        if ($auth->isAuth() === false) {
            $app->halt(403);
        }
    },
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
    function() use($app) {
        $auth = Middlewares\Authenticator::getInstance();

        if ($auth->isAuth() === false) {
            $app->halt(403);
        }
    },
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
    '/users/:uid/recipes',
    function() use($app) {
        $auth = Middlewares\Authenticator::getInstance();

        if ($auth->isAuth() === true) {
            $app->halt(403);
        }
    },
    function($uid) {
        $user = Repositories\Users::retrieveById($uid);
        $response = array('status' => false);

        if ($user) {
            $recipes = Repositories\Recipes::retrieveByUser($user['uuid']);
            $response = array('status' => true, 'data' => array('recipes' => array()));

            if ($recipes) {
                $response['data']['recipes'] = $recipes;
            }
        }

        echo json_encode($response);
    }
);

$app->post(
    '/register',
    function() use($app) {
        $auth = Middlewares\Authenticator::getInstance();

        if ($auth->isAuth() === true) {
            $app->halt(403);
        }
    },
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

$app->post(
    '/login',
    function() use($app) {
        $auth = Middlewares\Authenticator::getInstance();

        if ($auth->isAuth() === true) {
            $app->halt(403);
        }

        $response = array('status' => false, 'data' => array());

        $username = $app->request()->post('username');
        $password = $app->request()->post('password');

        if (!empty($username) && !empty($password)) {
            if ($auth->auth($username, $password)) {

                // get User
                $user = Repositories\Users::retrieveByUsername($username);

                $response['status'] = true;
                $response['user'] = $user;
                $response['data']['token'] = $auth->getToken();

            }
        }

        echo json_encode($response);
    }
);

$app->get(
    '/logout',
    function() use($app) {
        $auth = Middlewares\Authenticator::getInstance();

        if ($auth->isAuth() === false) {
            $app->halt(403);
        }
    },
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