<?php
namespace Cartrack\Services;

use Firebase\JWT\JWT;

class TokenService
{
	private $secretKey;
	private $algorithm;

	private $expiration;
	private $notBefore;

	public function __construct()
	{
		$currentTime = time();
		
		$this->secretKey = $_ENV['SECRET_KEY'];
		$this->algorithm = $_ENV['ALGORITHM'];

		$this->expiration = $_ENV['EXPIRATION'];
		$this->notBefore  = $_ENV['NOT_BEFORE'];
	}

	public function generate(array $data): string
	{
		$currentTime = time();

		$payload = array(
			"iat"       => $currentTime,
			"nbf"       => $currentTime + $this->notBefore,
			// "exp"       => $currentTime + $this->expiration,
			"data" 		=> $data
		);

		return JWT::encode($payload, $this->secretKey, $this->algorithm);
	}
}