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
include_once '../classes/user.php';
$database = new Database();
$db = $database->connect();

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    # get input data
    $input_data = json_decode(file_get_contents("php://input"));
    if (!empty($input_data->email) && !empty($input_data->password)) {

        $user = new User($db);

        # sanitize
        $user->email = htmlspecialchars(strip_tags($input_data->email));
        $user->password = htmlspecialchars(strip_tags($input_data->password));

        if ($user->login()) {

            $payload = array(
                "iss" => $issuer,
                "iat" => $issued_at,
                "exp" => $expiration_time,
                "data" => array(
                    "id" => $user->id,
                    "name" => $user->name,
                    "email" => $user->email
                )
            );
            # generate JWT token
            $jwt = JWT::encode($payload, $key);

            http_response_code(200);
            echo json_encode(
                array(
                    "status" => 1,
                    "message" => "Login Successful.",
                    "jwt" => $jwt
                )
            );
        } else {
            http_response_code(400);
            echo json_encode(array(
                "status" => 1,
                "message" => "Login Failed."
            ));
        }
    } else {
        http_response_code(400);
        echo json_encode(array(
            "status" => 0,
            "message" => "All Fields Are Mandatory."
        ));
    }
} else {
    http_response_code(401);
    echo json_encode(array(
        "status" => 0,
        "message" => "Invalid Request."
    ));
}

$database = new Database();

if (!empty($_POST['email']) && !empty($_POST['password'])) {

    $db = $database->connect();
    $user = new User($db);
    $user->email = htmlspecialchars(strip_tags($_POST['email']));
    $user->password = htmlspecialchars(strip_tags($_POST['password']));
}
