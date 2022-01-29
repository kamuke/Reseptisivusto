<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Recipe.php';
    include_once '../../includes/create-log.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate recipe object
    $recipe = new Recipe($db);

    // Get posted data
    $data = json_decode(file_get_contents("php://input"));

    // Check that data and usercookie are set
    if (isset($data->ID) && isset($data->ratedIndex) && isset($_COOKIE["usercookie"])) {
        $recipe->ID = $data->ID;
        $rating = $data->ratedIndex;
        $rating++;

        // Set rating value
        if ($recipe->setRecipesRating($rating)) {
            $newRating = $recipe->getRecipesRating();
            print_r(json_encode($newRating));
        } else {
            print_r(json_encode(array()));
        }
    } else {
        print_r(json_encode(array()));
    }