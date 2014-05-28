<?php
namespace Vrap\TheBlenderCommunity\Repositories;

class Recipes extends \Vrap\TheBlenderCommunity\Repository {
    /**
     * Retrieve all existing recipes
     * 
     * @return [Array] An array of recipes
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

        $recipes = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (false === $stmt) {
            return false;
        }

        return $recipes;
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

    /**
     * Remove a recipe.
     * 
     * @param  [String] $ruid The uuid of the recipe to remove
     * @return [Boolean]      Return true
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

        $stmt->execute();

        return true;
    }
}