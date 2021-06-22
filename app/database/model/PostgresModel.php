<?php 
namespace Cartrack\Database\Model;

use Cartrack\Database\Model\ModelInterface;
use PDO;

class PostgresModel implements ModelInterface
{
	private $db;
	private $stmt;
	private $table;

	protected function connect($dbConnection)
	{	
		$this->db = $dbConnection;
	}

	public function __destruct()
	{
		$this->db = null;
	}

	public function setTable(string $table): void
	{
		$this->table = $table;
	}
	
	public function getTable(): string
	{
		return $this->table;
	}

	public function insert(string $sql, array $params = array()): int
	{
		$this->query($sql, $params);
		return $this->lastInsertId();
	}

	public function select(string $sql, array $params = array()): array
	{
		$this->query($sql, $params);
		return $this->getRows();
	}

	public function update(string $sql, array $params = array()): int
	{
		$this->query($sql, $params);
		return $this->affectedRows();
	}

	public function delete(string $sql, array $params = array()): int
	{
		$this->query($sql, $params);
		return $this->affectedRows();
	}

	private function query(string $sql, array $params): void
	{
		try {
			$this->stmt = $this->db->prepare($sql);
			if (count($params) > 0) {
				$this->bindParam($params);
			}
			$this->stmt->execute();
		} catch (PDOException $e) {
			exit($e->getMessage());
		}
	}

	private function bindParam(array $params): void
	{
		foreach ($params as $key => &$val) {
			$this->stmt->bindParam(':' . $key, $val);
		}
	}

	public function getRows(): array
	{
		return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function affectedRows(): int
	{
		return $this->stmt->rowCount();
	}

	public function lastInsertId(): int
	{
		return $this->db->lastInsertId();
	}
}