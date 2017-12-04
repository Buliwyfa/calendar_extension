<?php

namespace humhub\modules\calendar_extension\models;

use ICal\ICal;
use Yii;
use humhub\modules\content\components\ContentActiveRecord;
use humhub\modules\calendar_extension\permissions\ManageCalendar;
use humhub\modules\content\models\Content;

/**
 * This is the model class for table "calendar_extension_calendar".
 *
 * @property integer $id
 * @property string $title
 * @property string $url
 * @property integer $public    Set if the Content should be public or private
 * @property string $time_zone The timeZone these entries was saved, note the dates itself are always saved in app timeZone
 * @property string $color
 * @property string $version    The ical-version, the calendar is stored
 * @property string $cal_name    The original calendar-name
 * @property string $cal_scale    The original calendar scale format, e.g. Gregorian
 *
 * property CalendarExtensionEvent[] $calendarExtensionEvents
 * @property CalendarExtensionCalendarEntry[] $CalendarExtensionCalendarEntries
 */
class CalendarExtensionCalendar extends ContentActiveRecord
{
    /**
     * @inheritdoc
     */
    public $wallEntryClass = "humhub\modules\calendar_extension\widgets\WallEntryCalendar";

    /**
     * @inheritdoc
     */
    public $managePermission = ManageCalendar::class;

    /**
     * Flag for Entry Form to set this content to public
     */
//    public $is_public = Content::VISIBILITY_PUBLIC;


    /**
     * @inheritdoc
     * set post to stream to false
     */
    public $streamChannel = null;
    public $silentContentCreation = true;

    /**
     *  init by settings
     */
    public function init()
    {
        parent::init();

        $this->setSettings();
    }

    public function setSettings()
    {
        // Set autopost settings for calendar
        $module = Yii::$app->getModule('calendar_extension');
        $autopost_calendar = $module->settings->get('autopost_calendar');

        if ($autopost_calendar) {
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
        return 'calendar_extension_calendar';
    }

    /**
     * @inheritdoc
     */
    public function getContentName()
    {
        return Yii::t('CalendarExtensionModule.base', "External Calendar");
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
            [['title', 'url'], 'string', 'max' => 255],
            [['time_zone'], 'string', 'max' => 60],
            [['color'], 'string', 'max' => 7],
            [['url'],'url','defaultScheme' => 'http', 'message' => Yii::t('CalendarExtensionModule.sync_result', "No valid ical url! Try an url with http / https.")],
            [['url'], 'validateURL'],
            [['public'], 'integer'],
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
            $this->addError($attribute, Yii::t('CalendarExtensionModule.sync_result', "No valid ical url! Try an url with http / https."));
        }
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['create'] = ['title', 'url', 'public'];
        $scenarios['admin'] = ['title', 'url', 'public'];
        return $scenarios;
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
            'public' => Yii::t('CalendarExtensionModule.base', 'Public'),
            'time_zone' => Yii::t('CalendarExtensionModule.base', 'Timezone'),
            'color' => Yii::t('CalendarExtensionModule.base', 'Color'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function getSearchAttributes()
    {
        return [
            'title' => $this->title,
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        return parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @inheritdoc
     */
    public function beforeDelete()
    {
        foreach (CalendarExtensionCalendarEntry::findAll(['calendar_id' => $this->id]) as $item) {
            $item->delete();
        }

        return parent::beforeDelete();
    }

    public function getUrl()
    {
        return $this->content->container->createUrl('//calendar_extension/calendar/view', array('id' => $this->id));
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
