<?php

namespace humhub\modules\calendar_extension\models;

use Yii;
use humhub\modules\content\components\ContentActiveRecord;
use humhub\components\ActiveRecord;
use DateInterval;
use DateTime;
use DateTimeZone;
use yii\helpers\Url;

/**
 * This is the model class for table "calendar_extension_entry".
 *
 * @property integer $id
 * @property string $organizer
 * @property string $last_modified
 * @property string $dtstamp
 * @property string $title
 * @property string $description
 * @property string $start_datetime
 * @property string $end_datetime
 * @property integer $all_day
 * @property string $uid
 */
class CalendarExtensionEntry extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'calendar_extension_entry';
    }

    public function getIcon()
    {
        return 'fa-calendar-o';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['last_modified', 'dtstamp', 'all_day'], 'required'],
            [['last_modified', 'dtstamp', 'start_datetime', 'end_datetime'], 'safe'],
            [['description'], 'string'],
            [['all_day'], 'integer'],
            [['organizer', 'title', 'uid'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('CalendarExtensionModule.base', 'ID'),
            'organizer' => Yii::t('CalendarExtensionModule.base', 'Organizer'),
            'last_modified' => Yii::t('CalendarExtensionModule.base', 'Last Modified'),
            'dtstamp' => Yii::t('CalendarExtensionModule.base', 'Dtstamp'),
            'title' => Yii::t('CalendarExtensionModule.base', 'Title'),
            'description' => Yii::t('CalendarExtensionModule.base', 'Description'),
            'start_datetime' => Yii::t('CalendarExtensionModule.base', 'Start Datetime'),
            'end_datetime' => Yii::t('CalendarExtensionModule.base', 'End Datetime'),
            'all_day' => Yii::t('CalendarExtensionModule.base', 'All Day'),
            'uid' => Yii::t('CalendarExtensionModule.base', 'UID'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function getUrl()
    {
        return Url::to('/calendar_extension/index/view', ['id' => $this->id]);
    }

    /**
     * @inheritdoc
     */
    public function beforeDelete()
    {
//        foreach (MeetingItem::findAll(['meeting_id' => $this->id]) as $item) {
//            $item->delete();
//        }
//
//        foreach (MeetingParticipant::findAll(['meeting_id' => $this->id]) as $participant) {
//            $participant->delete();
//        }

        return parent::beforeDelete();
    }



    public function getStart_DateDateTime()
    {
        return new DateTime($this->start_datetime, new DateTimeZone(Yii::$app->timeZone));
    }

    public function getEnd_DateDateTime()
    {
        $result = new DateTime($this->end_datetime, new DateTimeZone(Yii::$app->timeZone));
        if($result <= $this->getStart_DateDateTime()) {
            $result->add(new DateInterval('P1D'));
        }
        return $result;
    }

}
