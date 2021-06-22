<?php
namespace Cartrack\Controller;

use Cartrack\Models\User;
use Cartrack\Responses\Response;
use Cartrack\Services\TokenService as Token;

class LoginController 
{
    private $requestMethod;

    private $user;
    private $token;

    public function __construct(string $requestMethod)
    {
        $this->requestMethod = $requestMethod;

        $this->user = new User();
        $this->token = new Token();
    }

    public function validate(): void
    {
        switch ($this->requestMethod) {
			case 'POST':
                $this->login();
                break;
            default:
				Response::methodNotAllowed();
                break;
        }
    }

    private function login(): void
    {
		$credential = json_decode(file_get_contents('php://input'));

		if (! $this->validateCredential($credential)) {
            Response::unauthorized('Invalid credentials');
			return;
        }

		$result = $this->user->get($credential);
		$count = count($result);

		if ($count > 0) {
			$jwt = $this->token->generate($result);
			Response::success('Token generated', array('jwt' => $jwt));
			return;
		} else {
			Response::unauthorized('Invalid credentials');
			return;
		}
		
    }

	private function validateCredential(?object $credential): bool
    {
        if (
			! empty($credential->username) 
			&& ! empty($credential->password) 
		) {
			return true;
		}
		return false;
    }
}