<?php 
namespace Cartrack\Models;

use Cartrack\Database\Connection\PostgresConnection as Postgres;
use Cartrack\Database\Model\PostgresModel;

class User extends PostgresModel
{
    public function __construct()
    {
		$postgres = new Postgres;
		$conn = $postgres->connection();

		$this->connect($conn);
    }

    public function get(?object $credential): array
    {
		$sql = "SELECT user_id
				FROM api_person.users
				WHERE username = :username
				AND password = :password";

		$params = array(
			'username' => $credential->username,
			'password' => $credential->password,
		);

		return $this->selectQuery();
    }
}