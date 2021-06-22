<?php
namespace Cartrack\Responses;

class Response
{
	static function success(string $message = 'Success', array $data = array()): void
	{
		http_response_code(200);
		echo json_encode(
			array_merge (
				array(
					'status'  => 1,
					'message' => $message
				),
				$data
			)
		);
	}

	static function created(string $message = 'Created', array $data = array()): void
	{
		http_response_code(201);
		echo json_encode(
			array_merge (
				array(
					'status'  => 1,
					'message' => $message
				),
				$data
			)
		);
	}

	static function badRequest(string $message = 'Bad Request'): void
	{
		http_response_code(400);
		echo json_encode(
			array(
				'status'  => 0,
				'message' => $message
			)
		);
	}

	static function unauthorized(string $message = 'Unauthorized'): void
	{
		http_response_code(401);
		echo json_encode(
			array(
				'status'  => 0,
				'message' => $message
			)
		);
	}

	static function notFound(string $message = 'Not found'): void
	{
		http_response_code(404);
		echo json_encode(
			array(
				'status'  => 0,
				'message' => $message
			)
		);
	}

	static function methodNotAllowed(string $message = 'Method not allowed'): void
	{
		http_response_code(405);
		echo json_encode(
			array(
				'status'  => 0,
				'message' => $message
			)
		);
	}

	static function conflict(string $message = 'Conflict'): void
	{
		http_response_code(409);
		echo json_encode(
			array(
				'status'  => 0,
				'message' => $message
			)
		);
	}

	static function serverError(string $message = 'Internal Server Error'): void
	{
		http_response_code(500);
		echo json_encode(
			array(
				'status'  => 0,
				'message' => $message
			)
		);
	}
}