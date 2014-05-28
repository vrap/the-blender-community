<?php
class Users {
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

    public static function retrieveById($id) {

    }

    public static function delete($id) {

    }

    public static function register($id, $email, $password) {

    }
}