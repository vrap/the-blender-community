<?php
namespace Vrap\TheBlenderCommunity\Repositories;

class Recipes extends \Vrap\TheBlenderCommunity\Repository {
    

    /**
     * Save the recipe
     * @param  recipe $recipe
     * @return bool
     */
    public static function save($recipe){

        $recipeUuid = \Vrap\TheBlenderCommunity\Utils\UUID::v4();
        $sql = '
            INSERT INTO
                recipes (uuid, name, author, created, forked)
            VALUES
                (:uuid, :name, :author, NOW(), :forked)
        ';

        $stmt = self::getDatabase()->prepare($sql);
        $stmt->bindValue(':uuid', $recipeUuid);
        $stmt->bindValue(':name', $recipe->name);
        $stmt->bindValue(':author', $recipe->author);
        $stmt->bindValue(':forked', $recipe->forked);
        $result = $stmt->execute();

        // Call all steps and save them
        foreach ($recipe->steps as $steps) {
            RecipeSteps::save($recipeUuid, $steps);
        }

        return $result;

    }

    /**
     * Retrieve all existing recipes
     * @return Array An array with recipes data
     */
    public static function retrieveAll() {
        $sql = '
            SELECT
                r.uuid, `name`, `author`, `created`, `updated`, `forked`, `username`
            FROM
                `recipes` `r`
            INNER JOIN
                `users` `u`
            ON r.author = u.uuid
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
     * Retrieve all existing recipes
     * With all Step end Ingredient
     * @return Array An array with recipes data
     */
    public static function retrieveAllWithSteps() {

        // get all recipes
        $recipes = self::retrieveAll();

        // If no recipes return false
        if (false === $recipes) {
            return false;
        }

        // Read all Recipes
        for ($i=0; $i < count($recipes); $i++) {
            // Retrive his step
            $steps = RecipeSteps::retrieveByRecipe($recipes[$i]);

            if(false != $steps){

                // Put in recipe
                $recipes[$i]['steps'] = $steps;
                // Read all Steps
                for ($y=0; $y < count($steps); $y++) { 
                    // Put in Recipe/step
                    $parameters = RecipeStepValues::retrieveByRecipeStep($steps[$y]);

                    if(false != $parameters){
                        $recipes[$i]['steps'][$y]['parameters'] = $parameters;
                    }else{
                        $recipes[$i]['steps'][$y]['parameters'] = '';
                    }
                    
                }

            }else{
                $recipes[$i]['steps'] = '';
            }
            
        }

        // Return the yummy recipes
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

    /**
     * Count all existing recipes
     * 
     * @return Integer Number of recipes
     */
    public static function count() {
        $sql = '
            SELECT
                count(`uuid`)
            FROM
                `recipes`
        ';

        return (int) self::getDatabase()->query($sql)->fetchColumn();
    }

    public static function retrieveByName($name) {
        $sql = '
            SELECT
                `uuid`
            FROM
                `recipes`
            WHERE
                `name` = :name
        ';

        $stmt = self::getDatabase()->prepare($sql);
        $stmt->bindValue(':name', $name);

        $stmt->execute();

        $recipe = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (false === $stmt) {
            return false;
        }

        return $recipe;
    }
}