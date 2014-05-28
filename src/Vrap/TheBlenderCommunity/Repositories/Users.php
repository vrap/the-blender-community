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

    public static function delete($id) {

    }

    public static function register($id, $email, $password) {

    }
}