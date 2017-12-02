<?php

namespace humhub\modules\calendar_extension;

use humhub\modules\content\components\ContentContainerActiveRecord;
use humhub\modules\content\models\ContentContainer;
use Yii;
use humhub\modules\content\models\Content;
use humhub\modules\calendar_extension\CalendarUtils;
use humhub\modules\calendar_extension\models\CalendarExtensionCalendarEntry;
use humhub\modules\calendar_extension\models\CalendarExtensionCalendar;
use ICal\ICal;


/**
 * Description of SyncUtils
 *
 * @author luke
 */
class SyncUtils
{
    public static function createICal($url)
    {
        if (!isset($url)) {
            return false;
        }
        try {
            // load ical and parse it
            $ical = new ICal($url, array(
                'defaultSpan' => 2,     // Default value
                'defaultTimeZone' => Yii::$app->timeZone,
                'defaultWeekStart' => 'MO',  // Default value
                'skipRecurrence' => false, // Default value
                'useTimeZoneWithRRules' => false, // Default value
            ));

            return $ical;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * check for existing dbEntries and sync to list
     * entries that are no longer in list --> delete from db
     * @param array $models
     * @param $cal_id
     * @return bool
     */
    public static function checkAndSubmitModels(Array &$models, $cal_id)
    {
        if (!isset($models) && !isset($cal_id))
        {
            return false;
        }
        $dbModels = CalendarExtensionCalendarEntry::find()->where(['calendar_id' => $cal_id])->all();
        $keepInDb = [];

        // models is an array of CalendarExtensionCalendarEntry-Models
        foreach ($models as $key => $value)
        {
            $exists = false;
            foreach ($dbModels as $dbModel)
            {
                // search for existing entries in db
                if ($dbModel->uid == $value->uid && $dbModel->last_modified >= $value->last_modified) {
                    // if found and timestamp lower/identical--> nothing to change
                    array_push($keepInDb, $dbModel);
                    unset($models[$key]);
                    $exists = true;
                } elseif ($dbModel->uid == $value->uid) {
                    // check if uid exists and enty has been updated in ical
//                    $dbModel->updateByModel($value);
                    array_push($keepInDb, $dbModel);
                    $exists = true;
                }
            }
            if (!$exists) {
                $value->save();
            }

        }
        // remove arrays to keep in db
        foreach ($dbModels as $key => $val)
        {
            foreach ($keepInDb as $item)
            {
                if ($val === $item) {
                    unset($dbModels[$key]);
                }
            }
        }
        // finally delete items from db
        foreach ($dbModels as $model)
        {
            $model->delete();
        }
        unset($keepInDb);
        unset($dbModels);

        return true;
    }

    public static function getModels($events, CalendarExtensionCalendar $calendar)
    {
        // create Entry-models without safe
        $models = [];

        if (!isset($events) && !isset($calendar))
        {
            return $models;
        }
        foreach ($events as $event)
        {
            $model = new CalendarExtensionCalendarEntry();
            $model->uid = $event->uid;
            $model->calendar_id = $calendar->id;
            $model->title = $event->summary;
            $model->description = $event->description;
            $model->location = $event->location;
            $model->last_modified = CalendarUtils::formatDateTimeToString($event->last_modified);
            $model->dtstamp = CalendarUtils::formatDateTimeToString($event->dtstamp);
            $model->start_datetime = CalendarUtils::formatDateTimeToString($event->dtstart);
            $model->end_datetime = CalendarUtils::formatDateTimeToString($event->dtend);
            $model->time_zone = Yii::$app->timeZone;
            $model->all_day = CalendarUtils::checkAllDay($event->dtstart, $event->dtend);

            // add contentContainer of CalendarExtensionCalendar-Model
            $model->content->setContainer($calendar->content->container);
            $model->content->created_by = $calendar->content->created_by;
            $model->content->created_at = $model->dtstamp;  // set created_at to original created timestamp
            $model->content->visibility = ($calendar->public) ?  Content::VISIBILITY_PUBLIC : Content::VISIBILITY_PRIVATE;

            array_push($models, $model);
            unset($model);
        }

        return $models;
    }
}