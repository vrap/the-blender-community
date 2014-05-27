<?php
namespace Vrap\TheBlenderCommunity\Repositories;

class Recipes extends \Vrap\TheBlenderCommunity\Repository {
    public static function retrieveAll() {

    }

    public static function retrieveById($ruid) {
        $sql = '
            SELECT
                `uuid`, `name`, `author`, `created`, `updated`, `forked`
            FROM
                `recipes`
            WHERE
                `uuid` = :ruid
        ';

        $stmt = self::getDatabase()->prepare($sql);
        $stmt->bindValue(':ruid', $ruid);

        $stmt->execute();

        $recipe = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (false === $stmt) {
            return false;
        }

        return $recipe;
    }

    public static function retrieveByUser($uid) {

    }

    public static function delete($ruid) {

    }
}