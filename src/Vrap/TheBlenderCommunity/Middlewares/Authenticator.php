<?php
namespace Vrap\TheBlenderCommunity\Middlewares;

// Import the required namesapces.
use Vrap\TheBlenderCommunity\Utils\Singleton;
use Vrap\TheBlenderCommunity\Repositories;

class Authenticator {
    use Singleton;

    private $user;
    private $token = null;
    private $alreadyCheckedToken = false;

    public function setApp($app) {
        $this->app = $app;
    }

    public function auth($username, $password) {
        $user = Repositories\Users::retrieveByUsernameAndPassword(
            $username,
            $password
        );

        if ($user) {
            Repositories\UserSessions::removeByUser($user['uuid']);
            if (Repositories\UserSessions::save($user['uuid'])) {
                $userSession = Repositories\UserSessions::retrieveByUser($user['uuid']);
                $this->token = $userSession['token'];

                $this->app->setCookie('token', $this->token);

                return true;
            }
        }

        return false;
    }

    public function isAuth() {
        if (!$this->app) {
            return false;
        }

        $token = $this->app->getCookie('token');

        if ($token && $this->verifyToken($token)) {
            return true;
        }

        return false;
    }

    public function getToken() {
        return $this->token;
    }

    public function getUser() {
        if ($this->user) {
            return $this->user;
        }

        return null;
    }

    private function verifyToken($token) {
        if ($this->user || $this->alreadyCheckedToken) {
            return ($this->user) ? true : false;
        }

        $session = Repositories\UserSessions::retrieveByToken($token);
        $user    = Repositories\Users::retrieveById($session['user']);

        $this->alreadyCheckedToken = true;

        if ($user) {
            $this->user = $user;

            return true;
        }

        return false;
    }
}