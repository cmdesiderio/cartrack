<?php
require_once "./bootstrap.php";

use Cartrack\Controller\PersonController;
use Cartrack\Routes\Api as RoutesApi;
use Cartrack\Responses\Response;
use Cartrack\Services\FilterService;
use Firebase\JWT\JWT;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET,POST,PUT,DELETE");

$requestMethod = $_SERVER['REQUEST_METHOD'];
if (! in_array($requestMethod, array('GET','POST','PUT','DELETE'))) {
	Response::methodNotAllowed();
	return;
}

$headers = getallheaders();
$jwt = $headers['Authorization'];
if (empty($jwt)) {
	Response::unauthorized();
	return;
}

try {
	//validate JWT 
	$result = JWT::decode($jwt, $_ENV['SECRET_KEY'], array($_ENV['ALGORITHM']));
	$logId = (int) $result->data[0]->user_id;
} catch(Exception $e) {
	Response::unauthorized($e->getMessage());
	return;
}

//get id in url
$routes = new RoutesApi();
$data = $routes->uri();
$personId = $data['id'];

$filterData = array();
if (! $personId){
	$filterService = new FilterService($_GET);
	$filterData = $filterService->filterData();
}

$personController = new PersonController($requestMethod, $personId, $logId, $filterData);
$personController->processRequest();




