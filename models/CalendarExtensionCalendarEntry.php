<?php

namespace humhub\modules\calendar_extension\models;

use DateTimeZone;
use humhub\libs\Html;
use humhub\modules\search\interfaces\Searchable;
use Yii;
use DateTime;
use humhub\libs\DbDateValidator;
use humhub\widgets\Label;
use humhub\modules\content\components\ContentActiveRecord;
use humhub\modules\calendar_extension\permissions\ManageEntry;
use humhub\modules\calendar_extension\widgets\WallEntry;
use humhub\modules\content\models\Content;
use humhub\modules\calendar_extension\CalendarUtils;

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
class CalendarExtensionCalendarEntry extends ContentActiveRecord implements Searchable
{
    /**
     * @inheritdoc
     */
    public $wallEntryClass = WallEntry::class;

    /**
     * Flag for Entry Form to set this content to public
     */
    public $is_public = Content::VISIBILITY_PUBLIC;

    /**
     * @inheritdoc
     */
    public $managePermission = ManageEntry::class;

    /**
     * @inheritdoc
     * set post to stream to false
     */
    public $streamChannel = null;
    public $silentContentCreation = true;

    /**
     * @var CalendarDateFormatter
     */
    public $formatter;

    public function init()
    {
        parent::init();

        $this->setSettings();
        $this->formatter = new CalendarDateFormatter(['calendarItem' => $this]);
    }

    public function setSettings()
    {
        // Set autopost settings for entries
        $module = Yii::$app->getModule('calendar_extension');
        $autopost_entries = $module->settings->get('autopost_entries');

        if ($autopost_entries) {
            // set back to autopost true
            $this->streamChannel = 'default';
            $this->silentContentCreation = false;
        }
    }

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
    public function getContentName()
    {
        return Yii::t('CalendarExtensionModule.base', "Entry");
    }

    /**
     * @inheritdoc
     */
    public function getContentDescription()
    {
        return $this->title;
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
            [['location'], 'string'],
            [['end_datetime'], 'validateEndTime'],
            [['description'], 'safe'],
        ];
    }

    /**
     * Validator for the end_datetime field.
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
            'id' => Yii::t('CalendarExtensionModule.model_calendar_entry', 'ID'),
            'uid' => Yii::t('CalendarExtensionModule.model_calendar_entry', 'UID'),
            'calendar_id' => Yii::t('CalendarExtensionModule.model_calendar_entry', 'Calendar'),
            'title' => Yii::t('CalendarExtensionModule.model_calendar_entry', 'Title'),
            'description' => Yii::t('CalendarExtensionModule.model_calendar_entry', 'Description'),
            'location' => Yii::t('CalendarExtensionModule.model_calendar_entry', 'Location'),
            'last_modified' => Yii::t('CalendarExtensionModule.model_calendar_entry', 'Last Modified'),
            'dtstamp' => Yii::t('CalendarExtensionModule.model_calendar_entry', 'DT Stamp'),
            'start_datetime' => Yii::t('CalendarExtensionModule.model_calendar_entry', 'Start Datetime'),
            'end_datetime' => Yii::t('CalendarExtensionModule.model_calendar_entry', 'End Datetime'),
            'all_day' => Yii::t('CalendarExtensionModule.model_calendar_entry', 'All Day'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function getSearchAttributes()
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'location' => $this->location,
            'calendar' => $this->calendar->title,
        ];
    }

    public function beforeSave($insert)
    {
        // TODO: Check is a full day span --> Already done in AdminController->Sync
        if ($this->all_day == 0 && CalendarUtils::isFullDaySpan(new DateTime($this->start_datetime), new DateTime($this->end_datetime))) {
            $this->all_day = 1;
        }
        $end = new DateTime($this->end_datetime, new DateTimeZone(Yii::$app->timeZone));

        if ($this->all_day == 1 && $end->format('H:i:s') == '00:00:00') {
//            $date->setTime('23','59','59');
            $end->modify('-1 second');
        }
        $this->end_datetime = $end->format('Y-m-d H:i:s');

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

        if ($this->all_day) {
            $end = $end->modify('+1 second');
//            $end->setTime('00','00', '00');
        }

        return [
//            'id' => $this->id,
            'start' => $start,
            'end' => $end,
            'title' => Html::encode($this->getTitle()),
            'editable' => false,
            'icon' => 'fa-calendar-o',
            'allDay' => $this->isAllDay(),
            'viewUrl' => $this->content->container->createUrl('/calendar_extension/entry/view', ['id' => $this->id, 'cal' => '1']),
//            'updateUrl' => $this->content->container->createUrl('/calendar_extension/entry/update-ajax', ['id' => $this->id]),
            'openUrl' => $this->content->container->createUrl('/calendar_extension/entry/view', ['id' => $this->id]),
            'color' => $this->calendar->color, // overwrite color of Item_Type
//            'badge' => Label::asColor($this->calendar->color, $this->calendar->title)->icon('fa-calendar-o')->right(),    // change badge to name of external calendar
        ];
    }

    public function getUrl()
    {
        return $this->content->container->createUrl('/calendar_extension/entry/view', ['id' => $this->id]);
    }

    /**
     * @inheritdoc
     */
    public function getTimezone()
    {
        return $this->time_zone;
    }

    public function getStartDateTime()
    {
        return new DateTime($this->start_datetime, new DateTimeZone(Yii::$app->timeZone));
    }

    public function getEndDateTime()
    {
        return new DateTime($this->end_datetime, new DateTimeZone(Yii::$app->timeZone));
    }

    public function getFormattedTime($format = 'long')
    {
        return $this->formatter->getFormattedTime($format);
    }

    /**
     * @return boolean weather or not this item spans exactly over a whole day
     */
    public function isAllDay()
    {
        if ($this->all_day === null) {
            return true;
        }

        return (boolean)$this->all_day;
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
        $this->title = $model->title;
        $this->description = $model->description;
        $this->location = $model->location;
        $this->last_modified = $model->last_modified;
        $this->dtstamp = $model->dtstamp;
        $this->start_datetime = $model->start_datetime;
        $this->end_datetime = $model->end_datetime;
        $this->all_day = $model->all_day;
        $this->update();
    }

    public function findByUidAndCalAndTs()
    {
        return self::find()->where(['uid' => $this->uid])->andWhere(['calendar_id' => $this->calendar_id])->andWhere(['>=', 'last_modified', $this->last_modified])->one();
    }

    public function findByUidAndCal()
    {
        return self::find()->where(['uid' => $this->uid])->andWhere(['calendar_id' => $this->calendar_id])->one();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCalendar()
    {
        return $this->hasOne(CalendarExtensionCalendar::className(), ['id' => 'calendar_id']);
    }
}
