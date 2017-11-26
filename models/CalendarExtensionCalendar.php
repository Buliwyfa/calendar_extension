<?php

namespace humhub\modules\calendar_extension\models;

use ICal\ICal;
use Yii;
use humhub\components\ActiveRecord;

/**
 * This is the model class for table "calendar_extension_calendar".
 *
 * @property integer $id
 * @property string $title
 * @property string $url
 * @property string $time_zone The timeZone these entries was saved, note the dates itself are always saved in app timeZone
 * @property string $color
 * @property string $version    The ical-version, the calendar is stored
 * @property string $cal_name    The original calendar-name
 * @property string $cal_scale    The original calendar scale format, e.g. Gregorian
 *
 * property CalendarExtensionEvent[] $calendarExtensionEvents
 * @property CalendarExtensionCalendarEntry[] $CalendarExtensionCalendarEntries
 */
class CalendarExtensionCalendar extends ActiveRecord
{
    public $eventArray = [];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'calendar_extension_calendar';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'url'], 'string', 'max' => 255],
            [['time_zone'], 'string', 'max' => 60],
            [['color'], 'string', 'max' => 7],
            [['url'], 'validateURL'],
        ];
    }

    /**
     * Validator for the url field.
     *
     * @param string $attribute attribute name
     * @param array $params parameters
     */
    public function validateURL($attribute, $params)
    {
        try {
            new ICal($this->url, array(
                'defaultTimeZone' => Yii::$app->timeZone,
            ));
        } catch (\Exception $e) {
            $this->addError($attribute, Yii::t('CalendarExtensionModule.base', "No valid ical url!"));
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('CalendarExtensionModule.base', 'ID'),
            'title' => Yii::t('CalendarExtensionModule.base', 'Title'),
            'url' => Yii::t('CalendarExtensionModule.base', 'Url'),
            'time_zone' => Yii::t('CalendarExtensionModule.base', 'Timezone'),
            'color' => Yii::t('CalendarExtensionModule.base', 'Color'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeDelete()
    {
//        foreach (CalendarExtensionEvent::findAll(['calendar_id' => $this->id]) as $item) {
//            $item->delete();
//        }

        return parent::beforeDelete();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCalendarExtensionCalendarEntries()
    {
        return $this->hasMany(CalendarExtensionCalendarEntry::className(), ['calendar_id' => 'id']);
    }

    public function addAttributes(ICal $ical)
    {
        // add info to CalendarModel
        $this->time_zone = $ical->calendarTimeZone();
        $this->cal_name = $ical->calendarName();
        if (isset($ical->cal['VCALENDAR']['VERSION'])) {
            $this->version = $ical->cal['VCALENDAR']['VERSION'];
        }
        if (isset($ical->cal['VCALENDAR']['CALSCALE'])) {
            $this->cal_scale = $ical->cal['VCALENDAR']['CALSCALE'];
        }
    }
}
