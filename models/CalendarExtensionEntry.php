<?php

namespace humhub\modules\calendar_extension\models;

use Yii;
use humhub\components\ActiveRecord;
use DateTime;
use DateTimeZone;
use DateInterval;

/**
 * This is the model class for table "calendar_extension_entry".
 *
 * @property integer $id
 * @property string $uid
 * @property string $organizer
 * @property string $last_modified
 * @property string $dtstamp
 * @property string $title
 * @property string $description
 * @property string $dtstart
 * @property string $dtend
 */
class CalendarExtensionEntry extends ActiveRecord
{

    public function __construct(array $config = [])
    {
        if (isset($config) && $config != null) {
            $this->initialize($config);
        }
        parent::__construct($config);
    }

    /**
     * not sure if used..
     */
    public function getIcon()
    {
        return 'fa-calendar-o';
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'calendar_extension_entry';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'last_modified', 'dtstamp'], 'required'],
            [['last_modified', 'dtstamp', 'dtstart', 'dtend'], 'safe'],
            [['description'], 'string'],
            [['uid', 'organizer', 'title'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('base', 'ID'),
            'uid' => Yii::t('base', 'Uid'),
            'organizer' => Yii::t('base', 'Organizer'),
            'last_modified' => Yii::t('base', 'Last Modified'),
            'dtstamp' => Yii::t('base', 'Dtstamp'),
            'title' => Yii::t('base', 'Title'),
            'description' => Yii::t('base', 'Description'),
            'dtstart' => Yii::t('base', 'Dtstart'),
            'dtend' => Yii::t('base', 'Dtend'),
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

    /**
     * @inheritdoc
     */
    public function getStart_DateDateTime()
    {
        return new DateTime($this->dtstart, new DateTimeZone(Yii::$app->timeZone));
    }

    /**
     * @inheritdoc
     */
    public function getEnd_DateDateTime()
    {
        $result = new DateTime($this->dtend, new DateTimeZone(Yii::$app->timeZone));
        if($result <= $this->getStart_DateDateTime()) {
            $result->add(new DateInterval('P1D'));
        }
        return $result;
    }


    /**
     * this function will take a reference array $config and calls
     * the setters below to initialize the model with the results
     * from the iCal-Parser
     */
    public function initialize (array &$config)
    {
        foreach ($config as $key => $value)
        {
            switch ($key){
                case 'organizer':
                    $this->setOrganizer($value);
                    unset($config[$key]);
                    break;
                case 'last-modified':
                    $this->setLast_Modified($value);
                    unset($config[$key]);
                    break;
                case 'dtstamp':
                    $this->setDtstamp($value);
                    unset($config[$key]);
                    break;
                case 'summary':
                    $this->title = $value;
                    unset($config[$key]);
                    break;
                case 'description':
                    $this->setDescription($value);
                    unset($config[$key]);
                    break;
                case 'dtstart':
                    $this->setDtstart($value);
                    unset($config[$key]);
                    break;
                case 'dtend':
                    $this->setDtend($value);
                    unset($config[$key]);
                    break;
            }
        }
    }

    public function setDtstart($val)
    {
        $this->dtstart =  $this->formatTimestampToDateTime($val);
    }

    public function setDtend($val)
    {
        $this->dtend =  $this->formatTimestampToDateTime($val);
    }

    public function setLast_Modified($val)
    {
        $this->last_modified = $this->formatTimestampToDateTime($val);
    }

    public function setDtstamp($val)
    {
        $this->dtstamp = $this->formatTimestampToDateTime($val);
    }

    public function setDescription($val)
    {
//      // description is stored in an array... like:
//        [description] => Array
//        (
//            [0] =>
//        )

        $this->description =  $val[0];
    }

    public function setOrganizer($val)
    {
//      // organizer is stored like this:
//        [organizer] => Array
//        (
//            [mailto] =>
//            [CN] => "Samoski"
//        )
        // remove "-chars from organizer[CN]
        $this->organizer = str_replace('"', "", $val['CN']);
    }


    private function formatTimestampToDateTime($timestamp)
    {
        $date = new DateTime();
        $date->setTimestamp($timestamp);
        return $date->format('Y-m-d h:i:s');
//        return $date;
    }
}
