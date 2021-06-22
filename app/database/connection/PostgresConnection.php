<?php 

namespace Cartrack\Database\Connection;

use Cartrack\Database\Connection\ConnectionInterface;
use PDO;

class PostgresConnection implements ConnectionInterface
{
    public $conn;

    private $host;
    private $port;
    private $dbname;
    private $username;
    private $password ;

    public function __construct()
    {
        $this->host     = $_ENV['DB_HOST'];
        $this->port     = $_ENV['DB_PORT'];
        $this->dbname   = $_ENV['DB_DBNAME'];
        $this->username = $_ENV['DB_USERNAME'];
        $this->password = $_ENV['DB_PASSWORD'];
    }

    public function connect()
    {
        $this->conn = null;

        try {
			
			$dns = 'pgsql:host=' . $this->host . 
				   ';port=' . $this->port . 
				   ';dbname=' . $this->dbname . 
				   ';sslmode=require';

            $this->conn = new PDO($dns, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            return  "Error: " . $e->getMessage();
        }

		return $this->conn;   
    }

}