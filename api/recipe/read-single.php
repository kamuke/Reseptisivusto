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

    // Get ID
    $recipe->ID = isset($_GET['id']) ? $_GET['id'] : die();

    // Get recipe
    $recipe->getRecipe();

    // Check if any result
    if ($recipe->name!=null) {
    // Set response code - 200 OK
    http_response_code(200);

    // Convert to JSON & output
    print_r(json_encode($recipe));
    } else {
    // Set response code - 404 Not found
    http_response_code(404);

    // Output message
    echo json_encode(array('message' => 'Kyseistä reseptiä ei löytynyt tai sitä ei ole olemassa.'));
    }