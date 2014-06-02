<?php
namespace Vrap\TheBlenderCommunity\Repositories;

class UserSessions extends \Vrap\TheBlenderCommunity\Repository {
    public static function retrieveByToken($token) {
        $sql = '
            SELECT
                `user`, `token`
            FROM
                `user_sessions`
            WHERE
                `token` = :token
        ';

        $stmt = self::getDatabase()->prepare($sql);
        $stmt->bindValue(':token', $token);

        $stmt->execute();

        $userSession = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (false === $stmt) {
            return false;
        }

        return $userSession;
    }
}