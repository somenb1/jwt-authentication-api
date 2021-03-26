<?php
# include headers 
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

# include local files
include_once '../config/database.php';
include_once '../config/config.php';
include_once '../classes/user.php';
$database = new Database();

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    # get input data
    $input_data = json_decode(file_get_contents("php://input"));
    if (!empty($input_data->name) && !empty($input_data->email) && !empty($input_data->password)) {

        $db = $database->connect();
        $user = new User($db);

        # sanitize
        $user->name = htmlspecialchars(strip_tags($input_data->name));
        $user->email = htmlspecialchars(strip_tags($input_data->email));
        $user->password = htmlspecialchars(strip_tags($input_data->password));

        if ($user->registration()) {

            http_response_code(200);
            echo json_encode(
                array(
                    "status" => 1,
                    "message" => "Registration Successful."
                )
            );
        } else {
            http_response_code(400);
            echo json_encode(array(
                "status" => 1,
                "message" => "Registration Failed."
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
