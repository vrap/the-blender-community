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

    public static function retrieveByUser($uuid) {
        $sql = '
            SELECT
                `user`, `token`
            FROM
                `user_sessions`
            WHERE
                `user` = :uuid
        ';

        $stmt = self::getDatabase()->prepare($sql);
        $stmt->bindValue(':uuid', $uuid);

        $stmt->execute();

        $userSession = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (false === $stmt) {
            return false;
        }

        return $userSession;
    }

    public static function update($token) {
        $sql = '
            UPDATE
                `user_sessions`
            SET
                `updated_at` = NOW()
            WHERE
                `token` = :token
        ';

        $stmt = self::getDatabase()->prepare($sql);
        $stmt->bindValue(':token', $token);

        return $stmt->execute();
    }

    public static function save($uuid) {
        $sql = '
            INSERT INTO
                `user_sessions`
            (user, token, created_at)
            VALUES(:uuid, :token, NOW())
        ';
        $stmt = self::getDatabase()->prepare($sql);

        $stmt->bindValue(':uuid', $uuid);
        $stmt->bindValue(':token', bin2hex(openssl_random_pseudo_bytes(124)));

        return $stmt->execute();
    }

    public static function remove($token) {
        $sql = '
            DELETE
            FROM
                `user_sessions`
            WHERE
                `token` = :token
        ';

        $stmt = self::getDatabase()->prepare($sql);
        $stmt->bindValue(':token', $token);

        return $stmt->execute();
    }

    public static function removeByUser($uuid) {
        $sql = '
            DELETE
            FROM
                `user_sessions`
            WHERE
                `user` = :uuid
        ';

        $stmt = self::getDatabase()->prepare($sql);
        $stmt->bindValue(':uuid', $uuid);

        return $stmt->execute();
    }
}