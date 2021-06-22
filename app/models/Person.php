<?php 
namespace Cartrack\Models;

use Cartrack\Database\Connection\PostgresConnection as Postgres;
use Cartrack\Database\Model\PostgresModel as Model;

class Person extends Model
{
	public function __construct()
    {
		$postgres = new Postgres;
		$conn = $postgres->connect();

		$this->connect($conn);
    }

    public function store(object $input, int $logId): int
    {
		$sql = "INSERT INTO api_person.persons (
					first_name,
					last_name,
					email,
					birth_date,
					created_by_id,
					updated_by_id
				) VALUES (
					:first_name,
					:last_name,
					:email,
					:birth_date,
					:created_by_id,
					:updated_by_id
				)";

		$params = array(
			'first_name'    => $input->first_name,
			'last_name'     => $input->last_name,
			'email'         => $input->email,
			'birth_date'    => $input->birth_date,
			'created_by_id' => $logId,
			'updated_by_id' => $logId
		);

		return $this->insert($sql, $params);
    }

    public function get(?int $personId, array $filter): array
    {
		$sql = "SELECT person_id,
					first_name,
					last_name,
					AGE(birth_date) age,
					email,
					birth_date,
					created_by_id,
					updated_by_id,
					created_at,
					updated_at
				FROM api_person.persons
				WHERE person_id = COALESCE(:person_id, person_id)";

		$params = array(
			'person_id' => $personId,
		);

		// concat filter to sql and params
		if (! $personId && count($filter) > 0){
			$concatFilter = $this->concatFilter($filter);
			$sql .= $concatFilter[0];
			$params = array_merge($params, $concatFilter[1]);
		}

		return $this->select($sql, $params);
	}

    public function replace(?int $personId, int $logId, object $input): int
    {
		$sql = "UPDATE api_person.persons SET 
					first_name = :first_name,
					last_name = :last_name,
					email = :email,
					birth_date = :birth_date,
					updated_by_id = :updated_by_id
				WHERE person_id = :person_id";

		$params = array(
			'first_name'    => $input->first_name,
			'last_name'     => $input->last_name,
			'email'         => $input->email,
			'birth_date'    => $input->birth_date,
			'updated_by_id' => $logId,
			'person_id'     => $personId
		);

		return $this->update($sql, $params);
    }

    public function destroy(?int $personId): int
    {
		$sql = "DELETE FROM api_person.persons WHERE person_id = :person_id";
		$params = array('person_id' => $personId,);
		return $this->delete($sql, $params);
    }

	public function getEmail(string $email, ?int $personId)
    {
		$sql = "SELECT email FROM api_person.persons WHERE email = :email";
		$params = array('email' => $email);

		if ($personId) {
			$sql .= " AND person_id != :person_id";
			$params = array_merge($params, array('person_id' =>  $personId));
		}

		return $this->select($sql, $params);
    }

	private function concatFilter(array $filter): array
	{
		$sql = '';
		$params = array();

		foreach ($filter as $where) {
			if ($where['type'] == 'date') {
				if ($where['clause'] == 'where') {
					$sql .= " AND " . $where['name'] . " = :" . $where['name'];
					$params[$where['name']] = $where['value'];
				} else {
					$sql .= " AND " . $where['name'] . " BETWEEN :" . $where['name'] . '_from AND :' . $where['name'] . '_to';
					$data = explode( '/', $where['value']);
					$params[$where['name'].'_from'] = $data[0];
					$params[$where['name'].'_to'] =  $data[1];
				}
			} else {
				$sql .= " AND lower(" . $where['name'] . ") = lower(:" . $where['name'] . ")";
				$params[$where['name']] = $where['value'];
			}
		}
		return array($sql, $params);
	}
}