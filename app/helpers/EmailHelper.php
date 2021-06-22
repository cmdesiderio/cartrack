<?php  
namespace Cartrack\Helpers;

class EmailHelper
{
    static function validateEmailFormat($email): bool
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
			return true;
		}
		return false;
    }
}