<?php
require "./bootstrap.php";

use Cartrack\Controller\LoginController;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

$requestMethod = $_SERVER['REQUEST_METHOD'];

$loginController = new LoginController($requestMethod);
$loginController->validate();
