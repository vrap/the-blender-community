<?php
namespace Vrap\TheBlenderCommunity\Repositories;

class RecipeSteps extends \Vrap\TheBlenderCommunity\Repository {

    /**
     * Save the steps of recipe
     * @param  int $recipeUuid
     * @param  step $step
     * @return bool
     */
    public static function save($recipeUuid, $step){

        $db = self::getDatabase();

        $sql = '
            INSERT INTO
                `recipe_steps`  (`recipes_uuid`, `order`, `action`)
            VALUES
                (:recipesUuid, :order, :action)
        ';

        $stmt = $db->prepare($sql);

        $stmt->bindValue(':recipesUuid', $recipeUuid);
        $stmt->bindValue(':order', $step->order);
        $stmt->bindValue(':action', $step->action);
        $result = $stmt->execute();

        $recipeStepId = $db->lastInsertId();

        // Call all parameters and save them
        foreach ($step->parameters as $parameter) {
            RecipeStepValues::save($recipeStepId, $parameter);
        }

        return $result;

    }

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