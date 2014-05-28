<?php
namespace Vrap\TheBlenderCommunity\Repositories;

class Recipes extends \Vrap\TheBlenderCommunity\Repository {
    /**
     * Retrieve all existing recipes
     * 
     * @return Array An array with recipes data
     */
    public static function retrieveAll() {
        $sql = '
            SELECT
                `uuid`, `name`, `author`, `created`, `updated`, `forked`
            FROM
                `recipes`
        ';

        $stmt = self::getDatabase()->prepare($sql);
        $stmt->execute();

        $recipes = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        if (false === $stmt) {
            return false;
        }

        return $recipes;
    }

    /**
     * Retrieve a recipe.
     * 
     * @param  String $ruid The uuid of the recipe
     * @return Array        An array containing the recipe data
     */
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

    /**
     * Retrieve recipes of a user.
     * 
     * @param  String $uuid The uuid of the user
     * @return Array        An array with recipes data
     */
    public static function retrieveByUser($uuid) {
        $sql = '
            SELECT
                `uuid`, `name`, `author`, `created`, `updated`, `forked`
            FROM
                `recipes`
            WHERE
                `author` = :uuid
        ';

        $stmt = self::getDatabase()->prepare($sql);
        $stmt->bindValue(':uuid', $uuid);

        $stmt->execute();

        $recipes = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        if (false === $stmt) {
            return false;
        }

        return $recipes;
    }

    /**
     * Remove a recipe.
     * 
     * @param  String $ruid The uuid of the recipe
     * @return Boolean      Return true
     */
    public static function remove($ruid) {
        $sql = '
            DELETE
            FROM
                `recipes`
            WHERE
                `uuid` = :ruid
        ';

        $stmt = self::getDatabase()->prepare($sql);
        $stmt->bindValue(':ruid', $ruid);

        return $stmt->execute();
    }
}