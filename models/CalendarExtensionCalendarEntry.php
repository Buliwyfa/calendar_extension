<?php

namespace humhub\modules\calendar_extension\models;

use DateTimeZone;
use humhub\libs\Html;
use humhub\modules\calendar_extension\integration\calendar\CalendarExtensionQuery;
use Yii;
use DateTime;
use humhub\libs\DbDateValidator;
use humhub\components\ActiveRecord;
use yii\helpers\Url;

/**
 * This is the model class for table "calendar_extension_calendar_entry".
 *
 * The followings are the available columns in table 'calendar_extension_calendar_entry':
 * @property integer $id
 * @property string $uid
 * @property integer $calendar_id
 * @property string $title
 * @property string $description
 * @property string $location
 * @property string $last_modified
 * @property string $dtstamp
 * @property string $start_datetime
 * @property string $end_datetime It is the moment immediately after the event has ended. For example, if the last full day of an event is Thursday, the exclusive end of the event will be 00:00:00 on Friday!
 * @property string $time_zone
 * @property integer $all_day
 */
class CalendarExtensionCalendarEntry extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'calendar_extension_calendar_entry';
    }

    /**
     * @inheritdoc
     */
    public function getIcon()
    {
        return 'fa-calendar';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'start_datetime', 'end_datetime'], 'required'],
            [['last_modified'], DbDateValidator::className()],
            [['dtstamp'], DbDateValidator::className()],
            [['start_datetime'], DbDateValidator::className()],
            [['end_datetime'], DbDateValidator::className()],
            [['all_day'], 'integer'],
            [['title'], 'string', 'max' => 200],
            [['end_datetime'], 'validateEndTime'],
            [['description'], 'safe'],
        ];
    }

    /**
     * Validator for the endtime field.
     * Execute this after DbDateValidator
     *
     * @param string $attribute attribute name
     * @param array $params parameters
     */
    public function validateEndTime($attribute, $params)
    {
        if (new DateTime($this->start_datetime) >= new DateTime($this->end_datetime)) {
            $this->addError($attribute, Yii::t('CalendarExtensionModule.base', "End time must be after start time!"));
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('CalendarExtensionModule.base', 'ID'),
            'uid' => Yii::t('CalendarExtensionModule.base', 'UID'),
            'calendar_id' => Yii::t('CalendarExtensionModule.base', 'Kalender'),
            'title' => Yii::t('CalendarExtensionModule.base', 'Title'),
            'description' => Yii::t('CalendarExtensionModule.base', 'Description'),
            'location' => Yii::t('CalendarExtensionModule.base', 'Location'),
            'last_modified' => Yii::t('CalendarExtensionModule.base', 'Last Modified'),
            'dtstamp' => Yii::t('CalendarExtensionModule.base', 'DT Stamp'),
            'start_datetime' => Yii::t('CalendarExtensionModule.base', 'Start Datetime'),
            'end_datetime' => Yii::t('CalendarExtensionModule.base', 'End Datetime'),
            'all_day' => Yii::t('CalendarExtensionModule.base', 'All Day'),
        ];
    }

    public function beforeSave($insert)
    {
        // Check is a full day span TODO: Already done in CalendarExtensionICalArray
//        if ($this->all_day == 0 && CalendarUtils::isFullDaySpan(new DateTime($this->start_datetime), new DateTime($this->end_datetime))) {
//            $this->all_day = 1;
//        }
        $end = new DateTime($this->end_datetime, new DateTimeZone(Yii::$app->timeZone));

        if ($this->all_day == 1 && $end->format('H:i:s') == '00:00:00') {
//            $date->setTime('23','59','59');
//            $date->modify("+1 day");
            $end->modify('-1 second');
        }
        $this->end_datetime = $end->format('Y-m-d H:i:s');

        // TODO: always store as UTC

        return parent::beforeSave($insert);
    }

    public function beforeDelete()
    {
//        foreach (CalendarEntryParticipant::findAll(['calendar_entry_id' => $this->id]) as $participant) {
//            $participant->delete();
//        }

        return parent::beforeDelete();
    }

    /**
     * @inheritdoc
     */
    public function getFullCalendarArray()
    {
        $start = $this->getStartDateTime();
        $end = $this->getEndDateTime();

            if ($this->all_day)
            {
                $end = $end->modify('+2 hour');
//                $diff = $start->diff($end);
//                if ($diff->days > 1)
//                {
//                    $this->all_day=0;
////                    $end = $end->setTime('00','00', '00');
//                }
                $end->setTime('00','00');
            }

        $title = Html::encode($this->title);

            // TODO: change url to URL::to() --> if no pretty URL is activated
        return [
            'id' => $this->id,
            'title' => $this->getTitle(),
            'editable' => false,
            'icon' => 'fa-calendar-o',
            'allDay' => $this->all_day,
            'viewUrl' => '/calendar_extension/entry/modal?id=' . $this->id,
//            'updateUrl' => '/calendar_extension/entry/update?id=' . $this->id,
//            'openUrl' => '/calendar_extension/admin/view?id=' . $this->calendar->id,
            'start' => $start,
            'end' => $end,
            'color' => $this->calendar->color, // overwrite color of Item_Type
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCalendar()
    {
        return $this->hasOne(CalendarExtensionCalendar::className(), ['id' => 'calendar_id']);
    }

    /**
     * @inheritdoc
     */
    public function getTimezone()
    {
        if(!Yii::$app->user->isGuest) {
            Yii::$app->formatter->timeZone = Yii::$app->user->getIdentity()->time_zone;
        }
        return Yii::$app->formatter->timeZone;
    }

    public function getStartDateTime()
    {
        return new DateTime($this->start_datetime, new DateTimeZone(Yii::$app->timeZone));
    }

    public function getEndDateTime()
    {
        return new DateTime($this->end_datetime, new DateTimeZone(Yii::$app->timeZone));
    }

    /**
     * @return boolean weather or not this item spans exactly over a whole day
     */
    public function isAllDay()
    {
        if($this->all_day === null) {
            return true;
        }

        return (boolean) $this->all_day;
    }


    /**
     * Access url of the source content or other view
     *
     * @return string the timezone this item was originally saved, note this is
     */
    public function getTitle()
    {
        return $this->title;
    }



    public function updateByModel(CalendarExtensionCalendarEntry &$model)
    {
        $this->title              = $model->title;
        $this->description        = $model->description;
        $this->location           = $model->location;
        $this->last_modified      = $model->last_modified;
        $this->dtstamp            = $model->dtstamp;
        $this->start_datetime     = $model->start_datetime;
        $this->end_datetime       = $model->end_datetime;
        $this->all_day            = $model->all_day;
        $this->update();
    }

    public function findByUidAndCalAndTs ()
    {
        return self::find()->where(['uid' => $this->uid])->andWhere(['calendar_id' => $this->calendar_id])->andWhere(['>=', 'last_modified', $this->last_modified])->one();
    }

    public function findByUidAndCal()
    {
        return self::find()->where(['uid' => $this->uid])->andWhere(['calendar_id' => $this->calendar_id])->one();
    }
}
