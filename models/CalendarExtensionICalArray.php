<?php

namespace humhub\modules\calendar_extension\models;

use Yii;
use yii\base\Model;
use DateInterval;
use DateTime;
use DateTimeZone;
use yii\db\Exception;

// ToDo Copy Code
use humhub\modules\calendar\CalendarUtils;
use yii\helpers\ArrayHelper;

/**
 * This is the model class without a table. It represents the Array resulting from the iCAL-parser.
 *
 * @property string $uid
 * @property string $last_modified
 * @property string $dtstamp
 * @property string $summary
 * @property string $description
 * @property string $dtstart
 * @property string $dtend
 * @property string $location
 *
 */
// TODO: Den ganzen Code direkt bei "CalendarExtenstionEvent" --> Code gespart

class CalendarExtensionICalArray extends Model
{
    public $_uid;
    public $_dtstamp;
    public $_dtstart;
    public $_dtend;
    public $_description;
    public $_location;
    public $_summary;
    public $_last_modified;

    /**
     * @inheritdoc
     */
    public function __construct(array $config = [])
    {
        self::initialize($config);
        parent::__construct($config);
    }


    /**
     * this function will take a reference array $config and calls
     * the setters below to initialize the model with the results
     * from the iCal-Parser
     */
    public function initialize(array &$config)
    {
        foreach ($config as $key => $value) {
            switch ($key) {
                case 'uid':
                    break;
                case 'last-modified':
                    $config['last_modified'] = $config[$key];
                    unset($config[$key]);
                    break;
                case 'dtstamp':
                    break;
                case 'summary':
                    break;
                case 'description':
                    break;
                case 'dtstart':
                    break;
                case 'dtend':
                    break;
                case 'location':
                    break;
                default:
                    // unset all other values except the above..
                    unset($config[$key]);
            }
        }
    }


    public function setUid($val)
    {
        $this->_uid = $val;
    }

    public function getUid()
    {
        return $this->_uid;
    }

    public function setDtstamp($val)
    {
        $this->_dtstamp = $this->formatTimestampToDateTime($val);
    }

    /**
     * @return DateTime
     */
    public function getDtstamp()
    {
        return $this->_dtstamp->format('Y-m-d H:i:s');
    }

    public function setDtstart($val)
    {
        $this->_dtstart = $this->formatTimestampToDateTime($val);
    }

    /**
     * @return string
     */
    public function getDtstart()
    {
        return $this->_dtstart->format('Y-m-d H:i:s');
    }

    public function setDtend($val)
    {
        $this->_dtend = $this->formatTimestampToDateTime($val);
    }

    /**
     * @return string
     */
    public function getDtend()
    {
        return $this->_dtend->format('Y-m-d H:i:s');
    }

    public function setDescription($val)
    {
        $this->_description = $val[0];
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->_description;
    }

    public function setLocation($val)
    {
        $this->_location = $val;
    }

    /**
     * @return string
     */
    public function getLocation()
    {
        return $this->_location;
    }

    public function setSummary($val)
    {
        $this->_summary = $val;
    }

    /**
     * @return string
     */
    public function getSummary()
    {
        return $this->_summary;
    }

    public function setLast_Modified($val)
    {
        $this->_last_modified = $this->formatTimestampToDateTime($val);
    }

    /**
     * @return string
     */
    public function getLast_Modified()
    {
        return $this->_last_modified->format('Y-m-d H:i:s');
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('CalendarExtensionModule.base', 'ID'),
            'uid' => Yii::t('CalendarExtensionModule.base', 'UID'),
            'organizer' => Yii::t('CalendarExtensionModule.base', 'Organizer'),
            'last_modified' => Yii::t('CalendarExtensionModule.base', 'Last Modified'),
            'dtstamp' => Yii::t('CalendarExtensionModule.base', 'Dtstamp'),
            'summary' => Yii::t('CalendarExtensionModule.base', 'Title'),
            'description' => Yii::t('CalendarExtensionModule.base', 'Description'),
            'dtstart' => Yii::t('CalendarExtensionModule.base', 'Start Datetime'),
            'dtend' => Yii::t('CalendarExtensionModule.base', 'End Datetime'),
            'location' => Yii::t('CalendarExtensionModule.base', 'Location'),
        ];
    }

    /**
     *
     */
    private function formatTimestampToDateTime($timestamp)
    {
        if ($timestamp !== NULL) {
            try {
                $date = new DateTime();
                $date->setTimezone(new DateTimeZone(Yii::$app->timeZone));
                $date->setTimestamp(intval($timestamp));
                return $date;
            }
            catch (Exception $e) {
                return '';
            }
        }
        return '';
    }

    /**
     * format as array to equivalent model class
     */
    public function formatToArray()
    {
        $array = [];
        $array['uid'] = $this->uid;
        $array['last_modified'] = $this->last_modified;
        $array['dtstamp'] = $this->dtstamp;
        $array['title'] = $this->summary;
        $array['description'] = $this->description;
        $array['start_datetime'] = $this->dtstart;
        $array['end_datetime'] = $this->dtend;
        if (CalendarUtils::isFullDaySpan($this->_dtstart, $this->_dtend, true)) {
            $array['all_day'] = 1;
        } else {
            $array['all_day'] = 0;
        }
        $array['location'] = $this->location;
        return $array;
    }

}
