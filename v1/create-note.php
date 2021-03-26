<?php
# include headers 
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

# include jwt
require "../vendor/autoload.php";

use \Firebase\JWT\JWT;

# include local files
include_once '../config/database.php';
include_once '../config/config.php';
include_once '../classes/notes.php';
$database = new Database();
$db = $database->connect();

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    # get input data
    $input_data = json_decode(file_get_contents("php://input"));

    # get headers
    $headers = getallheaders();

    if (!empty($headers["Authorization"])) {

        $jwt =  $headers["Authorization"];
        try {
            $auth_data = JWT::decode($jwt, $key, array('HS256'));
            $notes = new Notes($db);
            # set the user id
            $notes->user_id = $auth_data->data->id;

            if (!empty($notes->user_id) && !empty($input_data->heading) && !empty($input_data->description) && !empty($input_data->type)) {

                $db = $database->connect();
                # sanitize
                $notes->heading = htmlspecialchars(strip_tags($input_data->heading));
                $notes->description = htmlspecialchars(strip_tags($input_data->description));
                $notes->type = htmlspecialchars(strip_tags($input_data->type));
                if ($notes->create()) {
                    http_response_code(200);
                    echo json_encode(
                        array(
                            "status" => 1,
                            "message" => "Note Saved Successfully."
                        )
                    );
                } else {
                    http_response_code(400);
                    echo json_encode(array(
                        "status" => 1,
                        "message" => "Failed to Save Note."
                    ));
                }
            } else {
                http_response_code(400);
                echo json_encode(array(
                    "status" => 0,
                    "message" => "All Fields Are Mandatory."
                ));
            }
        } catch (Exception $e) {
        }
    } else {
        http_response_code(400);
        echo json_encode(array(
            "status" => 0,
            "message" => "Authorization Failed."
        ));
    }
} else {
    http_response_code(401);
    echo json_encode(array(
        "status" => 0,
        "message" => "Invalid Request."
    ));
}
