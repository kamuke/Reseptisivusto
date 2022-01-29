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
    $order = $_GET['order'] ?? 0;
    $limit = $_GET['limit'] ?? 0;


    // Recipe query
    $result = $recipe->getRecipes(0, 0, 0, 0, 0, $order, $limit);

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