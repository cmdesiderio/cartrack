<?php
namespace Cartrack\Controller;

use Cartrack\Models\Person;
use Cartrack\Helpers\DateHelper;
use Cartrack\Helpers\EmailHelper;
use Cartrack\Responses\Response;

class PersonController 
{
    private $requestMethod;
	private $personId;
    private $logId;
    private $filter;

    private $person;

    public function __construct(string $requestMethod, ?int $personId, int $logId, array $filterData)
    {
        $this->requestMethod = $requestMethod;
        $this->personId = $personId;
        $this->logId = $logId;
        $this->filter = $filterData;

        $this->person = new Person();
    }

    public function processRequest(): void
    {
        switch ($this->requestMethod) {
			case 'POST':
                $this->create($this->logId);
                break;
            case 'GET':
				$this->read($this->personId, $this->filter);
              	break;
         	case 'PUT':
                $this->update($this->personId, $this->logId);
                break;
            case 'DELETE':
                $this->delete($this->personId);
                break;
            default:
				Response::methodNotAllowed();
                break;
        }
    }

	private function create(int $logId): Void
    {
        $input = json_decode(file_get_contents('php://input'));
        
		if (! $this->validateInput($input)) {
            Response::badRequest('Required data incomplete');
			return;
        }

		if (! DateHelper::validateDateFormat($input->birth_date)) {
            Response::badRequest('Invalid date');
			return;
        }

		if (! EmailHelper::validateEmailFormat($input->email)) {
            Response::badRequest('Invalid email');
			return;
        }

		if (! $this->validateDuplicateEmail($input->email)) {
			Response::conflict('Email already exist');
			return;
        }
		
		$lastId = $this->person->store($input, $logId);

		if ($lastId) {
			Response::created('Data created', array('id' => $lastId));
			return;
		} else {
			Response::serverError('Insert failed');
			return;
		}
    }

    private function read(?int $personId, array $filter): void
    {
		$result = $this->person->get($personId, $filter);
		$count = count($result);
		
		if ($count > 0) {
			Response::success('Data collected', array('count' => $count, 'data' => $result));
			return;
		} else {
			Response::notFound('Data not found');
			return;
		}
    }

	private function update(?int $personId, int $logId): void
	{
		$input = json_decode(file_get_contents('php://input'));

		if (! $this->validateInput($input)) {
            Response::badRequest('Required data incomplete');
			return;
        }

		if (! DateHelper::validateDateFormat($input->birth_date)) {
            Response::badRequest('Invalid date');
			return;
        }

		if (! EmailHelper::validateEmailFormat($input->email)) {
            Response::badRequest('Invalid email');
			return;
        }

		if (! $this->validateDuplicateEmail($input->email, $personId)) {
			Response::conflict('Email already exist');
			return;
        }

		if ($this->person->replace($personId, $logId, $input) > 0) {
			Response::success('Data updated');
			return;
		} else {
			Response::notFound('Data not found');
			return;
		}
	}

	private function delete(?int $personId): void
	{
		if ($this->person->destroy($personId) > 0) {
			Response::success('Data deleted');
			return;
		} else {
			Response::notFound('Data not found');
			return;
		}
	}

	private function validateDuplicateEmail(string $email, int $personId = null): bool
	{
		$result = $this->person->getEmail($email, $personId);
		$count = count($result);

		if($count == 0) {
			return true;
		}
		return false;
	}

    private function validateInput(?object $input): bool
    {
        if (
			! empty($input->first_name) 
			&& ! empty($input->last_name) 
			&& ! empty($input->email) 
			&& ! empty($input->birth_date) 
		) {
			return true;
		}
		return false;
    }
}