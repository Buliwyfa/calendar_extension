<?php

namespace humhub\modules\calendar_extension;

use Yii;
use DateTime;
use DateTimeZone;

/**
 * Description of CalendarUtils
 *
 * @author luke
 */
class CalendarUtils
{

    /**
     *
     * @param DateTime $date1
     * @param DateTime $date2
     * @param type $endDateMomentAfter
     * @return boolean
     */
    public static function isFullDaySpan(DateTime $date1, DateTime $date2, $endDateMomentAfter = false)
    {
        $dateInterval = $date1->diff($date2, true);

        if ($endDateMomentAfter) {
            if ($dateInterval->days > 0 && $dateInterval->h == 0 && $dateInterval->i == 0 && $dateInterval->s == 0) {
                return true;
            }
        } else {
            if ($dateInterval->h == 23 && $dateInterval->i == 59) {
                return true;
            }
        }


        return false;
    }

    public static function checkAllDay($dtstart, $dtend)
    {
        $start = self::formatDateTimeToAppTime($dtstart);
        $end = self::formatDateTimeToAppTime($dtend);

        if (self::isFullDaySpan($start, $end, true)) {
            return 1;
        } else {
            return 0;
        }
    }

    public static function formatDateTimeToAppTime($string)
    {
        $timezone = new DateTimeZone(Yii::$app->timeZone);
        $datetime = new DateTime($string);
        $datetime->setTimezone($timezone);
        return $datetime;
    }


    public static function formatDateTimeToUTC($string)
    {
        $timezone = new DateTimeZone('UTC');
        $datetime = new DateTime($string);
        $datetime->setTimezone($timezone);
        return $datetime;
    }


    public static function formatDateTimeToString($string)
    {
        $datetime = self::formatDateTimeToAppTime($string);
        return $datetime->format('Y-m-d H:i:s');
    }

}
