<?php
namespace Tests\Util;

use App\Library\Util\DateTimeZoneCustom;
use DateTime;
use DateTimeZone;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use PHPUnit\Metadata\Covers;

#[CoversClass(DateTimeZoneCustom::class)]
class DateTimeZoneCustomTest extends TestCase
{
    #[Covers('DateTimeZoneCustom::getCurrentDateTimeInString')]
    public function testGetCurrentDateTimeInString()
    {
        $currentDateTime = DateTimeZoneCustom::getCurrentDateTimeInString();
        $timezone = new DateTimeZone('America/Sao_Paulo');
        $date = new DateTime('now', $timezone);
        $expectedDateTime = $date->format('Y-m-d H:i:s');
        $this->assertEquals($expectedDateTime, $currentDateTime);
    }

    #[Covers('DateTimeZoneCustom::dateTimeToStringConverter')]
    public function testDateTimeToStringConverter()
    {
        $date = new DateTime('2024-11-08 15:30:00');
        $dateString = DateTimeZoneCustom::dateTimeToStringConverter($date);
        $expectedDateString = '2024-11-08 15:30:00';
        $this->assertEquals($expectedDateString, $dateString);
    }

    #[Covers('DateTimeZoneCustom::dateTimeToStringConverterWithoutSeconds')]
    public function testDateTimeToStringConverterWithoutSeconds()
    {
        $date = new DateTime('2024-11-08 15:30:00');
        $dateString = DateTimeZoneCustom::dateTimeToStringConverterWithoutSeconds($date);
        $expectedDateString = '2024-11-08 15:30';
        $this->assertEquals($expectedDateString, $dateString);
    }
    
    #[Covers('DateTimeZoneCustom::stringToDateTimeConverter')]
    public function testStringToDateTimeConverter()
    {
        $dateString = '2024-11-08 15:30:00';
        $date = DateTimeZoneCustom::stringToDateTimeConverter($dateString);
        $timezone = new DateTimeZone('America/Sao_Paulo');
        $expectedDate = new DateTime($dateString, $timezone);
        $this->assertEquals($expectedDate, $date);
    }
}
