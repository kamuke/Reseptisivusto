<?php

    /**
     * Recipe properties: ID, name, type, description, img, cookingtime,
     * servings, difficulty, author, published, modified, rating [ratingValue, ratingCount],
     * ingredients [amount, unit, name], instructions, tips, keywords [ID, name], 
     * categories [ID, name], diets [ID, name], similarRecipes
     */
    class Recipe {
        // DB
        private $conn;

        // Object properties
        public $ID;
        public $name;
        public $type;
        public $description;
        public $img;
        public $cookingtime;
        public $servings;
        public $difficulty;
        public $author;
        public $published;
        public $modified;
        public $rating;
        public $ingredients;
        public $instructions;
        public $tips;
        public $keywords;
        public $categories;
        public $diets;
        public $similarRecipes;

        /**
         * Constructor with DB connection.
        */
        public function __construct($db) {
            $this->conn = $db;
        }

        /**
         * Prepares and executes given sql query.
         * 
         * @param   string      $sql        The sql query to execute.
         * @param   int         $param      The param to bind to sql query.
         * @return  mixed                   Returns executed statement or false if something went wrong.
         */
        private function executeQuery($sql, $param) {
            
            try {
                // Prepare statement
                $stmt = $this->conn->prepare($sql);
                // Bind ID
                $stmt->bindParam(1, $param, PDO::PARAM_INT);
                // Execute sql query
                $stmt->execute();
            } catch(PDOException $e) {
                createLog($e->getMessage());
                return false;
            }

            return $stmt;
        }

        /**
         * Get one recipe and put data into recipe objects properties: 
         * ID, name, type, description, img, cookingtime, servings, difficulty,
         * author, published, modified, rating, ingredients, instructions, tips,
         * keywords, categories and diets.
         */
        public function getRecipe() {
            // Create sql query
            $sql = "SELECT * 
                    FROM recipe
                    WHERE ID = ? AND recipe.ispublished = 1
                    LIMIT 1";
            
            // Execute query
            $stmt = $this->executeQuery($sql, $this->ID);
            // Fetch in associative array
            $recipe = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($recipe) {
                // Set object properties
                $this->ID = $recipe['ID'];
                $this->name = $recipe['name'];
                $this->type = $recipe['type'];
                $this->description = $recipe['description'];
                $this->img = $recipe['img'];
                $this->cookingtime = $recipe['cookingtime'];
                $this->servings = $recipe['servings'];
                $this->difficulty = $recipe['difficulty'];
                $this->author = $recipe['author'];
                $this->published = $recipe['published'];
                $this->modified = $recipe['modified'];
                $this->rating = $this->getRecipesRating();
                $this->ingredients = $this->getRecipesIngredients();
                $this->instructions = $this->getRecipesInstructions();
                $this->tips = $this->getRecipesTips();
                $this->keywords = $this->getRecipesKeywords();
                $this->categories = $this->getRecipesCategories();
                $this->diets = $this->getRecipesDiets();
                $this->similarRecipes = $this->getRecipesWithSameTags();
            } else {
                createLog("Could not find recipe with ID = " . $this->ID . ".");
            }
        }

        /**
         * Get recipes in array or false if something went wrong.
         *
         * @param   mixed   $search         Array of the search input to use in where clause, or 0 if not using search input in where condition.
         * @param   mixed   $category       Array of the category names to use in where clause, or 0 if not using category in where condition.
         * @param   mixed   $diet           Array of the diet names to use in where clause, or 0 if not using diet in where condition.
         * @param   mixed   $difficulty     Array of the difficulty values (1,2 or 3) to use in where clause, or 0 if not using difficulty in where condition.
         * @param   mixed   $cookingtime    Array of the cookingtime values (in minutes. 1h = 60) to use in where clause, or 0 if not using cookingtime in where condition.
         * @param   int     $order          The order of the records: 1 order by published
         * @param   int     $limit          The limit of how many records to get: 0 all records
         * @return  mixed                   Array or false if something went wrong. Keys: ID, type, name,
         *                                  description, img, cookingtime, servings, difficulty, published, ratingValue.
         */
        public function getRecipes($search, $category, $diet, $difficulty, $cookingtime, $order, $limit) {
            // Get where condition clause
            $whereClause = getWhereConditionClause($search, $category, $diet, $difficulty, $cookingtime);

            // Create sql query
            $sql = "SELECT  recipe.ID,
                            recipe.type,
                            recipe.name,
                            recipe.description,
                            recipe.img,
                            recipe.cookingtime,
                            recipe.servings,
                            recipe.difficulty,
                            recipe.published,
                            IFNULL(AVG(recipeRating.rating),0) AS ratingValue
                    FROM recipe
                    LEFT JOIN recipeRating ON recipe.ID = recipeRating.recipeID
                    LEFT JOIN recipeKeyword ON recipe.ID = recipeKeyword.recipeID
                    LEFT JOIN keyword ON keyword.ID = recipeKeyword.keywordID
                    LEFT JOIN recipeCategory ON recipe.ID = recipeCategory.recipeID
    				LEFT JOIN category ON category.ID = recipeCategory.categoryID
                    LEFT JOIN recipeDiet ON recipe.ID = recipeDiet.recipeID
    				LEFT JOIN diet ON diet.ID = recipeDiet.dietID
                    $whereClause
                    GROUP BY recipe.ID";

            // Add order to to sql query depending on $order param
            switch ($order) {
                case 1:
                    $sql = $sql . " ORDER BY published DESC";
                    break;

                default:
                    break;
            }

            // Add limit to sql query depending on $order param
            switch (true) {
                case ($limit > 0):
                    $sql = $sql . " LIMIT :limit";
                    break;

                default:
                    break;
            }

            try {
                // Prepare statement
                $stmt = $this->conn->prepare($sql);

                // Bind values

                if ($search) {
                    foreach ($search as $key => $value) {
                        $namePlaceholder = ":name" . $key;
                        $descriptionPlaceholder = ":description" . $key;
                        $keywordPlaceholder = ":keyword" . $key;
                        $searchWithPercentageChars = "%" . $value. "%";
                        $stmt->bindValue($namePlaceholder, $searchWithPercentageChars, PDO::PARAM_STR);
                        $stmt->bindValue($descriptionPlaceholder, $searchWithPercentageChars, PDO::PARAM_STR);
                        $stmt->bindValue($keywordPlaceholder, $searchWithPercentageChars, PDO::PARAM_STR);
                    }
                }

                if($category) {
                    foreach ($category as $key => $value) {
                        $placeholder = ":category" . $key;
                        $stmt->bindValue($placeholder, $value, PDO::PARAM_STR);
                    }
                }

                if ($diet) {
                    foreach ($diet as $key => $value) {
                        $placeholder = ":diet" . $key;
                        $stmt->bindValue($placeholder, $value, PDO::PARAM_STR);
                    }
                }

                if ($difficulty) {
                    foreach ($difficulty as $key => $value) {
                        $placeholder = ":difficulty" . $key;
                        $stmt->bindValue($placeholder, $value, PDO::PARAM_INT);
                    }
                }

                if ($cookingtime) {
                    foreach ($cookingtime as $key => $value) {
                        $placeholder = ":cookingtime" . $key;
                        $stmt->bindValue($placeholder, $value, PDO::PARAM_INT);
                    }
                }

                if ($limit > 0) {
                    $stmt->bindValue(":limit", $limit, PDO::PARAM_INT);
                }

                createLog($sql);

                // Execute sql query
                $stmt->execute();
            } catch(PDOException $e) {
                createLog($e->getMessage());
                return false;
            }

            return $stmt;
        }

        /**
         * Get recipe's rating value and count in an associative array.
         * 
         * @return  array               Returns an associative array of the rating,
         *                              or an empty array if recipe could not be found in database.
         *                              Array's keys: ratingValue, ratingCount.
         */
        public function getRecipesRating() {
            // Create sql query
            $sql = "SELECT  IFNULL(AVG(rating),0) AS ratingValue,
                            COUNT(rating) AS ratingCount
                    FROM recipeRating
                    WHERE recipeID = ?";

            // Execute query
            $stmt = $this->executeQuery($sql, $this->ID);
            // Fetch in associative array
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                createLog("Recipe " . $this->ID . ": Got recipe's rating values successfully.");
                return $result;
            } else {
                createLog("Empty: getRecipesRating() got no results with ID = " . $this->ID . ".");
                $emptyArr = array();
                return $emptyArr;
            }
        }

        /**
         * Get recipe's ingredients in an associative array.
         * 
         * @return  array               Returns an associative array of the ingredients, 
         *                              or an empty array if recipe could not be found in database.
         *                              Array's keys: amount, unit, name.
         */
        private function getRecipesIngredients() {
            // Create sql query
            $sql = "SELECT  amount, 
                            unit, 
                            name
                    FROM ingredient
                    WHERE recipeID = ?";
            
            // Execute query
            $stmt = $this->executeQuery($sql, $this->ID);
            // Get row count
            $num = $stmt->rowCount();

            // Check if any and fetch all and return, if not return false
            if ($num > 0) {
                $ingredientsArr = array();

                while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    // Check if any empty values and change them into empty strings
                    foreach ($result as $key => $value) {
                        if (!isset($value)) {
                            $result[$key] = "";
                        }
                    }

                    extract($result);
                    $ingredient = $result;
                    array_push($ingredientsArr, $ingredient);
                }

                createLog("Recipe " . $this->ID . ": Got recipe's ingredients successfully.");
                return $ingredientsArr;
            } else {
                createLog("Empty: getRecipesIngredients() got no results with ID = " . $this->ID . ".");
                $emptyArr = array();
                return $emptyArr;
            }
        }

        /**
         * Get recipe's instructions in an array.
         * 
         * @return  array               Returns an array of the instructions, 
         *                              or an empty array if recipe could not be found in database.
         */
        private function getRecipesInstructions() {
            // Create sql query
            $sql = "SELECT instruction
                    FROM instruction
                    WHERE recipeID = ?
                    ORDER BY ID";

            // Execute query
            $stmt = $this->executeQuery($sql, $this->ID);

            // Get row count
            $num = $stmt->rowCount();

            // Check if any and fetch all and return, if not return false
            if ($num > 0) {
                $instructionsArr = array();

                while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($result);
                    $instructionItem = $instruction;
                    array_push($instructionsArr, $instructionItem);
                }

                createLog("Recipe " . $this->ID . ": Got recipe's instructions successfully.");
                return $instructionsArr;
            } else {
                createLog("Empty: getRecipesInstructions() got no results with ID = " . $this->ID . ".");
                $emptyArr = array();
                return $emptyArr;
            }
        }

        /**
         * Get recipe's tips in an array.
         * 
         * @return  array               Returns an array of the tips, 
         *                              or an empty array if recipe could not be found in database.
         */
        private function getRecipesTips() {
            // Create sql query
            $sql = "SELECT tip
                    FROM recipeTip
                    WHERE recipeID = ?";

            // Execute query
            $stmt = $this->executeQuery($sql, $this->ID);
            // Get row count
            $num = $stmt->rowCount();

            // Check if any and fetch all and return, if not return false
            if ($num > 0) {
                $tipsArr = array();

                while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($result);
                    $tipItem = $tip;
                    array_push($tipsArr, $tipItem);
                }

                createLog("Recipe " . $this->ID . ": Got recipe's tips successfully.");
                return $tipsArr;
            } else {
                createLog("Empty: getRecipesTips() got no results with ID = " . $this->ID . ".");
                $emptyArr = array();
                return $emptyArr;
            }
        }

        /**
         * Get recipe's keywords in an associative array.
         * 
         * @return  array               Returns an associative array of the keywords, 
         *                              or an empty if recipe could not be found in database.
         *                              Array's keys: ID, name.
         */
        private function getRecipesKeywords() {
            // Create sql query
            $sql = "SELECT keyword.ID, keyword.name
                    FROM keyword
                    JOIN recipeKeyword ON keyword.ID = recipeKeyword.keywordID
                    WHERE recipeID = ?";

            // Execute query
            $stmt = $this->executeQuery($sql, $this->ID);
            // Get row count
            $num = $stmt->rowCount();

            // Check if any and fetch all and return, if not return false
            if ($num > 0) {
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                createLog("Recipe " . $this->ID . ": Got recipe's keywords successfully.");
                return $result;
            } else {
                createLog("Empty: getRecipesKeywords() got no results with ID = " . $this->ID . ".");
                $emptyArr = array();
                return $emptyArr;
            }
        }

        /**
         * Get recipe's categories in an associative array.
         * 
         * @return  array               Returns an associative array of the categories, 
         *                              or an empty array if recipe could not be found in database.
         *                              Array's keys: ID, name.
         */
        private function getRecipesCategories() {
            // Create sql query
            $sql = "SELECT category.ID, category.name
                    FROM category
                    JOIN recipeCategory ON category.ID = recipeCategory.categoryID
                    WHERE recipeID = ?";

            // Execute query
            $stmt = $this->executeQuery($sql, $this->ID);
            // Get row count
            $num = $stmt->rowCount();

            // Check if any and fetch all and return, if not return false
            if ($num > 0) {
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                createLog("Recipe " . $this->ID . ": Got recipe's categories successfully.");
                return $result;
            } else {
                createLog("Empty: getRecipesCategories() got no results with ID = " . $this->ID . ".");
                $emptyArr = array();
                return $emptyArr;
            }
        }

        /**
         * Get recipe's diets in an associative array.
         * 
         * @return  array               Returns an associative array of the diets, 
         *                              or an empty array if recipe could not be found in database.
         *                              Array's keys: ID, name.
         */
        private function getRecipesDiets() {
            // Create sql query
            $sql = "SELECT diet.ID, diet.name
                    FROM diet
                    JOIN recipeDiet ON diet.ID = recipeDiet.dietID
                    WHERE recipeID = ?";

            // Execute query
            $stmt = $this->executeQuery($sql, $this->ID);
            // Get row count
            $num = $stmt->rowCount();

            // Check if any and fetch all and return, if not return false
            if ($num > 0) {
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                createLog("Recipe " . $this->ID . ": Got recipe's diets successfully.");
                return $result;
            } else {
                createLog("Empty: getRecipesDiets() got no results with ID = " . $this->ID . ".");
                $emptyArr = array();
                return $emptyArr;
            }
        }

        /**
         * Set new rating value to the selected recipe if the user hasn't rated it already.
         * 
         * @param   int     $rating     The rating value to add to the recipe.
         * 
         * @return  boolean             Returns true if new rating was added successfully, 
         *                              or false if the user has already rated it.
         */
        public function setRecipesRating($rating) {
            // Create sql query
            $sql = "SELECT *
                    FROM recipeRating
                    WHERE usercookie LIKE ? AND recipeID = ?";

            // Get usercookie
            $usercookie = $_COOKIE["usercookie"];
            // Sanitize usercookie
            $usercookie = htmlspecialchars($usercookie);

            try {
                // Prepare statement
                $stmt = $this->conn->prepare($sql);
                // Bind ID, usercookie and rating
                $stmt->bindParam(1, $usercookie, PDO::PARAM_STR);
                $stmt->bindParam(2, $this->ID, PDO::PARAM_INT);
                // Execute sql query
                $stmt->execute();
            } catch(PDOException $e) {
                createLog($e->getMessage());
                return false;
            }

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$result) {
                $sql = "INSERT INTO recipeRating(recipeID, usercookie, rating)
                        VALUES (?, ?, ?)";

                try {
                    // Prepare statement
                    $stmt = $this->conn->prepare($sql);
                    // Bind ID, usercookie and rating
                    $stmt->bindParam(1, $this->ID, PDO::PARAM_INT);
                    $stmt->bindParam(2, $usercookie, PDO::PARAM_STR);
                    $stmt->bindParam(3, $rating, PDO::PARAM_INT);
                    // Execute sql query
                    $stmt->execute();
                } catch(PDOException $e) {
                    createLog($e->getMessage());
                    return false;
                }

                createLog("Recipe " . $this->ID . ": User added new rating value of $rating.");
                return true;
            } else {
                return false;
            }
        }

        /**
         * Get recipes with same keywords, categories and diets in random order, or false if something went wrong.
         * 
         * @return  array           Array of the recipes or empty array if something went wrong. Keys: ID, type, name,
         *                          description, img, cookingtime, servings, difficulty, published, ratingValue.
         */
        public function getRecipesWithSameTags() {
            // Get recipe's ID, keywords, categories and diets
            $keyword = $this->getRecipesKeywords();
            $category = $this->getRecipesCategories();
            $diet = $this->getRecipesDiets();

            // Get where condition clause
            $where = getWhereConditionClauseForSameTags($keyword, $category, $diet);

            // Create sql query
            $sql = "SELECT  recipe.ID,
                            recipe.type,
                            recipe.name,
                            recipe.description,
                            recipe.img,
                            recipe.cookingtime,
                            recipe.servings,
                            recipe.difficulty,
                            recipe.published,
                            IFNULL(AVG(recipeRating.rating), 0) AS ratingValue
                    FROM recipe
                    LEFT JOIN recipeRating ON recipe.ID = recipeRating.recipeID
                    JOIN recipeKeyword ON recipe.ID = recipeKeyword.recipeID
                    JOIN recipeCategory ON recipe.ID = recipeCategory.recipeID
                    JOIN recipeDiet ON recipe.ID = recipeDiet.recipeID
                    $where
                    GROUP BY recipe.ID
                    ORDER BY RAND()
                    LIMIT 3";

            try {
                // Prepare statement
                $stmt = $this->conn->prepare($sql);

                // Bind values
                $stmt->bindValue(":id", $this->ID, PDO::PARAM_INT);

                foreach ($keyword AS $key => $value) {
                    $placeholder = ":keyword" . $key;
                    $stmt->bindValue($placeholder, $value['ID'], PDO::PARAM_INT);
                }

                foreach ($category AS $key => $value) {
                    $placeholder = ":category" . $key;
                    $stmt->bindValue($placeholder, $value['ID'], PDO::PARAM_INT);
                }

                foreach ($diet AS $key => $value) {
                    $placeholder = ":diet" . $key;
                    $stmt->bindValue($placeholder, $value['ID'], PDO::PARAM_INT);
                }

                createLog($sql);

                // Execute sql query
                $stmt->execute();
            } catch(PDOException $e) {
                createLog($e->getMessage());
                $emptyArr = array();
                return $emptyArr;
            }

            $num = $stmt->rowCount();

            if ($num > 0) {
                $recipesArr = array();

                while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                  extract($row);

                  $item = array(
                    'ID' => $ID,
                    'type' => $type,
                    'name' => $name,
                    'img' => $img,
                    'description' => $description,
                    'cookingtime' => $cookingtime,
                    'servings' => $servings,
                    'difficulty' => $difficulty,
                    'published' => $published,
                    'ratingValue' => $ratingValue
                  );

                  array_push($recipesArr, $item);
                }

                createLog("Recipe " . $this->ID . ": Got recipes with same tags successfully.");
                return $recipesArr;
            } else {
                createLog("Could not find recipes with same tags for recipe with ID = " . $this->ID . ".");
                $emptyArr = array();
                return $emptyArr;
            }
        }
    }

    /**
     * Get where condition clause or empty string if all params are empty.
     * 
     * @param   array   $keyword    Array of the keywords to use in where clause, or empty array if not using keywords in where condition.
     * @param   array   $category   Array of the categories to use in where clause, or empty array if not using category in where condition.
     * @param   array   $diet       Array of the diets to use in where clause, or empty array if not using diet in where condition.
     * @return  string              Return where condition clause
     */
    function getWhereConditionClauseForSameTags($keyword, $category, $diet) {
        $arrWhere = array();

        if ($keyword) {
            $str = "";

            for ($i = 0; $i < count($keyword); $i++) {
                // If $i is 0
                if ($i === 0) {
                    $str = "recipeKeyword.keywordID = :keyword" . $i; 
                } else {
                    $str = $str . " OR recipeKeyword.keywordID = :keyword" . $i;
                }
            }

            $str = "(" . $str . ")";
            array_push($arrWhere, $str);
        }

        if ($category) {
            $str = "";

            for ($i = 0; $i < count($category); $i++) {
                // If $i is 0
                if ($i === 0) {
                    $str = "recipeCategory.categoryID = :category" . $i; 
                } else {
                    $str = $str . " OR recipeCategory.categoryID = :category" . $i;
                }
            }

            $str = "(" . $str . ")";
            array_push($arrWhere, $str);
        }

        if ($diet) {
            $str = "";

            for ($i = 0; $i < count($diet); $i++) {
                // If $i is 0
                if ($i === 0) {
                    $str = "recipeDiet.dietID = :diet" . $i; 
                } else {
                    $str = $str . " OR recipeDiet.dietID = :diet" . $i;
                }
            }

            $str = "(" . $str . ")";
            array_push($arrWhere, $str);
        }

        if (count($arrWhere) > 0) {
            $strWhereClause = "WHERE recipe.ispublished = 1 AND ";

            foreach ($arrWhere as $key => $value) {
                if ($key === array_key_first($arrWhere)) {
                    $strWhereClause = $strWhereClause . $value . " AND NOT (recipe.ID = :id)";
                } else {
                    $strWhereClause = $strWhereClause . " OR " . $value . " AND NOT (recipe.ID = :id)"; 
                }
            }

            return $strWhereClause;
        } else {
            return "WHERE recipe.ispublished = 1";
        }
    }

    /**
     * Get where condition clause or empty string if all search params are 0.
     *
     * @param   mixed   $search         String of the search input to use in where clause, or 0 if not using search input in where condition.
     * @param   mixed   $category       Array of the category names to use in where clause, or 0 if not using category in where condition.
     * @param   mixed   $diet           Array of the diet names to use in where clause, or 0 if not using diet in where condition.
     * @param   mixed   $difficulty     Array of the difficulty values (1,2 or 3) to use in where clause, or 0 if not using difficulty in where condition.
     * @param   mixed   $cookingtime    Array of the cookingtime values (in minutes. 1h = 60) to use in where clause, or 0 if not using cookingtime in where condition.
     * @return  string                  Return where condition clause (for example: "WHERE (category.name LIKE ...)")
     */
    function getWhereConditionClause($search, $category, $diet, $difficulty, $cookingtime) {
        $arrWhere = array();

        if ($search) {
            $strName = "";
            $str = "";

            for ($i = 0; $i < count($search); $i++) {

                if ($i === 0) {
                    $strName =  "recipe.name LIKE :name" . $i;
                    $str = "recipe.description LIKE :description" . $i . " OR keyword.name LIKE :keyword" . $i;
                } else {
                    $strName = $strName . " AND " . "recipe.name LIKE :name" . $i;
                    $str = $str . " OR " . "recipe.description LIKE :description" . $i . " OR keyword.name LIKE :keyword" . $i;
                }
            }
            $strName = "(" . $strName . ")";
            $str = "(" . $str . ")";
            $str = $strName . " OR " . $str;
            array_push($arrWhere, $str);
        }

        if ($category) {
            $str = "";

            for ($i = 0; $i < count($category); $i++) {

                if ($i === 0) {
                    $str = "category.name LIKE :category" . $i;
                } else {
                    $str = $str . " OR category.name LIKE :category" . $i;
                }
            }

            $str = "(" . $str . ")";
            array_push($arrWhere, $str);
        }

        if ($diet) {
            $str = "";

            for ($i = 0; $i < count($diet); $i++) {

                if ($i === 0) {
                    $str = "diet.name LIKE :diet" . $i;
                } else {
                    $str = $str . " OR diet.name LIKE :diet" . $i;
                }
            }

            $str = "(" . $str . ")";
            array_push($arrWhere, $str);
        }

        if ($difficulty) {
            $str = "";

            for ($i = 0; $i < count($difficulty); $i++) {

                if ($i === 0) {
                    $str = "difficulty = :difficulty" . $i;
                } else {
                    $str = $str . " OR difficulty = :difficulty" . $i;
                }
            }

            $str = "(" . $str . ")";
            array_push($arrWhere, $str);
        }

        if ($cookingtime) {
            $str = "";

            for ($i = 0; $i < count($cookingtime); $i++) {

                if ($i === 0) {
                    $str = "cookingtime <= :cookingtime" . $i;
                } else {
                    $str = $str . " OR cookingtime <= :cookingtime" . $i;
                }
            }

            $str = "(" . $str . ")";
            array_push($arrWhere, $str);
        }

        if (count($arrWhere) > 0) {
            $strWhereClause = "WHERE recipe.ispublished = 1 AND ";

            foreach ($arrWhere as $key => $value) {
                if ($key === array_key_first($arrWhere)) {
                    $strWhereClause = $strWhereClause . $value;
                } else {
                    $strWhereClause = $strWhereClause . " AND " . $value;
                }
            }

            return $strWhereClause;
        } else {
            return "WHERE recipe.ispublished = 1";
        }
    }