<?php
namespace Vrap\TheBlenderCommunity\Repositories;

class RecipeSteps extends \Vrap\TheBlenderCommunity\Repository {
    /**
     * Retrieve a recipe.
     * 
     * @param  String $ruid The uuid of the recipe
     * @return Array        An array containing the recipe data
     */
    public static function retrieveByRecipe($recipe) {
        $sql = '
            SELECT
                `id`, `order`, `action`
            FROM
                `recipe_steps`
            WHERE
                `recipes_uuid` = :ruid
            ORDER BY
                `order` ASC
        ';

        $stmt = self::getDatabase()->prepare($sql);
        $stmt->bindValue(':ruid', $recipe['uuid']);

        $stmt->execute();

        $steps = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        if (false === $stmt) {
            return false;
        }

        return $steps;
    }
}