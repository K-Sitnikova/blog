<?php

namespace api;
include_once ("../Repository/UserRepository.php");

use UserRepository;
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");

$requestMethod = $_SERVER["REQUEST_METHOD"];
$controller = new UserController();
if($requestMethod === "GET") {
    $id = $_GET["id"];
    $controller->getUserById($id);
}
if ($requestMethod === "POST") {
    $user = $_POST["user"];
    $controller->createUser($user);
}
if ($requestMethod === "PUT") {
    $user = $_POST["user"];
    $controller->updateUser($user);
}
if ($requestMethod === "DELETE") {
    $user = $_POST["id"];
    $controller->deleteUser($user);
}

class UserController
{
    function __construct()
    {
        $this->userRepo = new UserRepository();
    }

    private $userRepo;
    public function getUserById($id)
    {
        if(isset($id)){
            $data = $this->userRepo->getUser($id);
            if($data != null) {
                http_response_code(200);
                echo json_encode($data);
            }else {
                http_response_code(404);
                echo json_encode(["message" => "User not found"]);
            }
        }
    }

    public function createUser($user)
    {

    }

    public function updateUser($user)
    {

    }

    public function deleteUser($user)
    {

    }
}