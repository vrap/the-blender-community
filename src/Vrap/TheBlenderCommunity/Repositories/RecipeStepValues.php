<?php
namespace Vrap\TheBlenderCommunity\Repositories;

class RecipeStepValues extends \Vrap\TheBlenderCommunity\Repository {
    /**
     * Retrieve a recipe.
     * 
     * @param  String $ruid The uuid of the recipe
     * @return Array        An array containing the recipe data
     */
    public static function retrieveByRecipeStep($recipe_step) {
        $sql = '
            SELECT
                `name`, `value`
            FROM
                `recipe_step_values`
            WHERE
                `recipe_steps_id` = :id
        ';

        $stmt = self::getDatabase()->prepare($sql);
        $stmt->bindValue(':id', $recipe_step['id']);

        $stmt->execute();

        $params = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        if (false === $stmt) {
            return false;
        }

        return $params;
    }
}