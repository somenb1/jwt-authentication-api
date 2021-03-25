<?php
# include headers 
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

# include jwt
require "../vendor/autoload.php";

use \Firebase\JWT\JWT;

# include local files
include_once '../config/database.php';
include_once '../config/config.php';
include_once '../classes/notes.php';
$database = new Database();
$db = $database->connect();

if ($_SERVER['REQUEST_METHOD'] === "GET") {

    $notes = new Notes($db);
    $headers = getallheaders();

    if (!empty($headers["Authorization"])) {
        // personal notes
        $jwt =  $headers["Authorization"];
        try {
            $auth_data = JWT::decode($jwt, $key);
            $notes->user_id = $auth_data->data->id;
            $my_notes = $notes->get_my_notes();
            if (!empty($my_notes)) {
                http_response_code(200);
                echo json_encode(array(
                    "status" => 1,
                    "notes" => $my_notes
                ));
            } else {
                http_response_code(400);
                echo json_encode(array(
                    "status" => 0,
                    "message" => "No Notes Found."
                ));
            }
        } catch (Exception $ex) {
            http_response_code(500);
            echo json_encode(array(
                "status" => 0,
                "message" => $ex->getMessage()
            ));
        }
    } else {
        $public_notes = $notes->get_public_notes();
        if (!empty($my_notes)) {
            http_response_code(200);
            echo json_encode(array(
                "status" => 1,
                "notes" => $public_notes
            ));
        } else {
            http_response_code(400);
            echo json_encode(array(
                "status" => 0,
                "message" => "No Notes Found."
            ));
        }
    }
} else {
    http_response_code(401);
    echo json_encode(array(
        "status" => 0,
        "message" => "Invalid Request."
    ));
}
