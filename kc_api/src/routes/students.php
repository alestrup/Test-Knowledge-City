<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// This part can be improved, converting the content of the routes to classes

// Get all students
$app->get('/students', function(Request $request, Response $response) {

    
    $limit = intval($request->getParam('limit'));
    $current_page = intval($request->getParam('current_page'));

    if ($limit == 0)
        $limit = 5;

    if ($current_page == 0)
        $current_page = 1;

    $offset = ($current_page - 1) * $limit;

    $sql = "SELECT * FROM students LIMIT " . $offset .",". $limit;

    try {
        $db = new Connection();
        $db = $db->connect();

        $stmt = $db->query($sql);
        $students = $stmt->fetchAll(PDO::FETCH_OBJ);

        $sql = "SELECT * FROM students";
        $stmt = $db->query($sql);

        $total_rows = $stmt->rowCount();
        $total_pages = ceil($total_rows / $limit);

        $db = null;
        
        echo json_encode(
            [
                "per_page" => $limit,
                "current_page" => $current_page,
                "last_page" => $total_pages,
                "data" => $students
            ]
        );

    } catch (PDOException $e) {
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

// Get single students
$app->get('/student/{id}', function(Request $request, Response $response) {
    $id = $request->getAttribute('id');

    $sql = "SELECT * FROM students WHERE id = $id";

    try {
        $db = new Connection();
        $db = $db->connect();

        $stmt = $db->query($sql);
        $student = $stmt->fetchAll(PDO::FETCH_OBJ);

        $db = null;

        echo json_encode($student);
    } catch (PDOException $e) {
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});