<?php
namespace Vrap\TheBlenderCommunity\Middlewares;

// Import the required namesapces.
use Vrap\TheBlenderCommunity\Utils\Singleton;
use Vrap\TheBlenderCommunity\Repositories;

class Authenticator {
    use Singleton;

    private $user;
    private $alreadyCheckedToken = false;

    public function setApp($app) {
        $this->app = $app;
    }

    public function isAuth() {
        if (!$this->app) {
            return false;
        }

        $token = $this->app->getCookie('token');

        if ($token && $this->getUser($token)) {
            return true;
        }

        return false;
    }

    private function getUser($token) {
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