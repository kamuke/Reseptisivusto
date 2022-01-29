<?php

    /**
     * Carousel object properties: ID, name, recipes [ID, type, name, description, img,
     * cookingtime, servings, difficulty].
     */
    class Carousel {
        // DB
        private $conn;

        // Object properties
        public $ID;
        public $name;
        public $recipes;

        /**
         * Constructor with DB connection.
        */
        public function __construct($db) {
            $this->conn = $db;
        }

        /**
         * Get one carousel's recipes and put them into carousel object's properties.
         */
        public function getCarousel() {
            // Create sql query
            $sql = "SELECT  carousel.name AS carouselName,
                            recipe.ID,
                            recipe.type,
                            recipe.name,
                            recipe.description,
                            recipe.img,
                            recipe.cookingtime,
                            recipe.servings,
                            recipe.difficulty,
                            recipe.published,
                            IFNULL(AVG(recipeRating.rating),0) AS ratingValue
                    FROM carousel
                    JOIN carouselRecipe ON carousel.ID = carouselRecipe.carouselID
                    JOIN recipe ON carouselRecipe.recipeID = recipe.ID
                    LEFT JOIN recipeRating ON recipe.ID = recipeRating.recipeID
                    WHERE carousel.ID = ? AND carousel.ispublished = 1
                    GROUP BY recipe.ID
                    ORDER BY RAND()";

            try {
                // Prepare statement
                $stmt = $this->conn->prepare($sql);
                // Bind $ID
                $stmt->bindParam(1, $this->ID, PDO::PARAM_INT);
                // Execute sql query
                $stmt->execute();
            } catch(PDOException $e) {
                createLog($e->getMessage());
                return false;
            }

            $num = $stmt->rowCount();

            if ($num > 0) {
                $carouselArr = array();

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

                  array_push($carouselArr, $item);
                  $caName = $carouselName;
                }

                // Set object properties
                $this->name = $caName;
                $this->recipes = $carouselArr;
            } else {
                createLog("Could not find carousel with ID = " . $this->ID . ".");
            }
        }
    }