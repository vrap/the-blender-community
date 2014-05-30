<?php
namespace Vrap\TheBlenderCommunity\Repositories;

class Users extends \Vrap\TheBlenderCommunity\Repository {
    /**
     * Retrieve all existing recipes
     * 
     * @return Array An array with recipes data
     */
    public static function retrieveAll() {
        $sql = '
            SELECT
                `uuid`, `username`
            FROM
                `users`
        ';

        $stmt = self::getDatabase()->prepare($sql);
        $stmt->execute();

        $users = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        if (false === $stmt) {
            return false;
        }

        return $users;
    }

    public static function retrieveById($uuid) {
        $sql = '
            SELECT
                `uuid`, `username`
            FROM
                `users`
            WHERE
                `uuid` = :uuid
        ';

        $stmt = self::getDatabase()->prepare($sql);
        $stmt->bindValue(':uuid', $uuid);

        $stmt->execute();

        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (false === $stmt) {
            return false;
        }

        return $user;
    }

    public static function retrieveByEmail($email) {
        $sql = '
            SELECT
                `uuid`
            FROM
                `users`
            WHERE
                `email` = :email
        ';

        $stmt = self::getDatabase()->prepare($sql);
        $stmt->bindValue(':email', $email);

        $stmt->execute();

        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (false === $stmt) {
            return false;
        }

        return $user;
    }

    public static function retrieveByUsername($username) {
        $sql = '
            SELECT
                `uuid`
            FROM
                `users`
            WHERE
                `username` = :username
        ';

        $stmt = self::getDatabase()->prepare($sql);
        $stmt->bindValue(':username', $username);

        $stmt->execute();

        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (false === $stmt) {
            return false;
        }

        return $user;
    }

    /**
    * Save a user to database.
    *
    * @param String $username A username
    * @param String $email An email address
    * @param String $password The password of the user
    */
    public static function save($username, $email, $password) {
        $sql = '
            INSERT INTO
                `users`
            (uuid, username, email, password)
            VALUES(UUID(), :username, :email, :password)
        ';
        $stmt = self::getDatabase()->prepare($sql);

        $stmt->bindValue(':username', $username);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':password', base64_encode(openssl_digest($password, 'sha512')));

        return $stmt->execute();
    }

    /**
     * Count all existing users
     * 
     * @return Integer Number of users
     */
    public static function count() {
        $sql = '
            SELECT
                count(`uuid`)
            FROM
                `users`
        ';

        return (int) self::getDatabase()->query($sql)->fetchColumn();
    }
}