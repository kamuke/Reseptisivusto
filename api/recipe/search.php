<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Recipe.php';
    include_once '../../includes/create-log.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate recipe object
    $recipe = new Recipe($db);

    // Get params
    $search = $_GET['search'] ?? 0;

    if ($search) {
        $search = explode(" ", $search);
    }

    $category = getArrayIfNotZero($_GET['category'] ?? 0);
    $diet = getArrayIfNotZero($_GET['diet'] ?? 0);
    $difficulty = getArrayIfNotZero($_GET['difficulty'] ?? 0);
    $cookingTime = getArrayIfNotZero($_GET['cookingtime'] ?? 0);
    $order = 1;
    $limit = 0;

    // Recipe query
    $result = $recipe->getRecipes($search, $category, $diet, $difficulty, $cookingTime, $order, $limit);

    // Get row count
    $num = $result->rowCount();

    // Check if any
    if ($num > 0) {
        // Create array for recipes
        $recipesArr = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            // Extract row
            extract($row);

            // Crete item array
            $item = array(
                'ID' => $ID,
                'name' => $name,
                'type' => $type,
                'description' => $description,
                'img' => $img,
                'cookingtime' => $cookingtime,
                'servings' => $servings,
                'difficulty' => $difficulty,
                'published' => $published,
                'ratingValue' => $ratingValue
            );

            // Push the item array to recipesArr
            array_push($recipesArr, $item);
        }

        // Set response code - 200 OK
        http_response_code(200);

        // Convert to JSON & output
        echo json_encode($recipesArr);
    } else {
        // Set response code - 404 Not found
        http_response_code(404);

        // Output message
        echo json_encode(array('message' => 'Hakutuloksia ei löytynyt.'));
    }

    /**
     * Gets array of the given $value seperated by "," or 0 if the given value is 0.
     *
     * @param   mixed   String of the $_GET value or 0.
     * @return  mixed   Return array of $value seperated by "," or return 0 if the given $value is 0.
     */
    function getArrayIfNotZero($value) {
        if ($value) {
            $arr = explode(",", $value);
            return $arr;
        } else {
            return 0;
        }
    }