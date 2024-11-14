<?php
namespace App\Library\Util;

use DateTime;
use DateTimeZone;

class DateTimeZoneCustom
{

    public static function getCurrentDateTimeInString()
    {
        $timezone = new DateTimeZone('America/Sao_Paulo');
        $date = new DateTime('now', $timezone);
        $currentDateTime = $date->format('Y-m-d H:i:s');
        
        return $currentDateTime;
        
    }
    public static function dateTimeToStringConverter(DateTime $date): string
    {
        $currentDateTime = $date->format('Y-m-d H:i:s');
        
        return $currentDateTime;
    }

    public static function dateTimeToStringConverterWithoutSeconds(DateTime $date): string
    {
        $currentDateTime = $date->format('Y-m-d H:i');
        
        return $currentDateTime;
        
    }
    
    public static function stringToDateTimeConverter(string $date)
    {
        $timezone = new DateTimeZone('America/Sao_Paulo');
        $date = new DateTime($date, $timezone);
        
        return $date;
    }

}
