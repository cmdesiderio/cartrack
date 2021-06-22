<?php  
namespace Cartrack\Helpers;

use DateTime;

class DateHelper
{
    static function validateDateFormat($date, $format = 'Y-m-d'): bool
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }
}