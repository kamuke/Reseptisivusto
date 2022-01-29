<?php 
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Carousel.php';
    include_once '../../includes/create-log.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate blog post object
    $carousel = new Carousel($db);

    // Get ID
    $carousel->ID = isset($_GET['id']) ? $_GET['id'] : die();

    // Get recipe
    $carousel->getCarousel();

    // Check if any result
    if ($carousel->name!=null) {
    // Set response code - 200 OK
    http_response_code(200);

    // Convert to JSON & output
    print_r(json_encode($carousel));
    } else {
    // Set response code - 404 Not found
    http_response_code(404);

    // Output message
    echo json_encode(array('message' => 'Hakutuloksia ei löytynyt.'));
    }