<?php 
namespace Cartrack\Models;

use Cartrack\Database\Connection\PostgresConnection as Postgres;
use Cartrack\Database\Model\PostgresModel as Model;

class User extends Model
{
    public function __construct()
    {
		$postgres = new Postgres;
		$conn = $postgres->connect();

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
			'password' => $credential->password
		);

		return $this->select($sql, $params);
    }
}