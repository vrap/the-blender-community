<?php
namespace Vrap\TheBlenderCommunity\Repositories;

class RecipeStepValues extends \Vrap\TheBlenderCommunity\Repository {

    /**
     * Save the value of the step
     * @param  int $recipeStepId
     * @param  value $recipeStepValues
     * @return bool
     */
    public static function save($recipeStepId, $recipeStepValues){

        $sql = '
            INSERT INTO 
               recipe_step_values (`recipe_steps_id`, `name`, `value`)
            VALUES 
                (:recipeStepId, :name, :value)
        ';

        $stmt = self::getDatabase()->prepare($sql);
        $stmt->bindValue(':recipeStepId', $recipeStepId);
        $stmt->bindValue(':name', $recipeStepValues->name);
        $stmt->bindValue(':value', $recipeStepValues->value);

        $result = $stmt->execute();
        return  $result;

    }


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