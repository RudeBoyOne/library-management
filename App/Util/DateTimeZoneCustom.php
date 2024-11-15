<?php
namespace App\Library\Util;

use DateTime;
use DateTimeZone;

/**
 * Class DateTimeZoneCustom
 * 
 * Provides utility functions for handling date and time with specific timezone settings. 
 * 
 */
class DateTimeZoneCustom
{

    /**
     * Gets the current date and time as a string in 'America/Sao_Paulo' timezone.
     * 
     * @return string The current date and time in 'Y-m-d H:i:s' format. 
     */
    public static function getCurrentDateTimeInString()
    {
        $timezone = new DateTimeZone('America/Sao_Paulo');
        $date = new DateTime('now', $timezone);
        $currentDateTime = $date->format('Y-m-d H:i:s');
        
        return $currentDateTime;
        
    }

    /**
     * Converts a DateTime object to a string in 'Y-m-d H:i:s' format.
     * @param DateTime $date The DateTime object to convert.
     * @return string The formatted date and time string. 
     */
    public static function dateTimeToStringConverter(DateTime $date): string
    {
        $currentDateTime = $date->format('Y-m-d H:i:s');
        
        return $currentDateTime;
    }

    /**
     * Converts a DateTime object to a string in 'Y-m-d H:i' format (without seconds).
     * 
     * @param DateTime $date The DateTime object to convert.
     * @return string The formatted date and time string without seconds. 
     */
    public static function dateTimeToStringConverterWithoutSeconds(DateTime $date): string
    {
        $currentDateTime = $date->format('Y-m-d H:i');
        
        return $currentDateTime;
        
    }
    
    /**
     * Converts a string to a DateTime object in 'America/Sao_Paulo' timezone.
     * 
     * @param string $date The date string to convert.
     * @return DateTime The DateTime object. 
     */
    public static function stringToDateTimeConverter(string $date)
    {
        $timezone = new DateTimeZone('America/Sao_Paulo');
        $date = new DateTime($date, $timezone);
        
        return $date;
    }

}
