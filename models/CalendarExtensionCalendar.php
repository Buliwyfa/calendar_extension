<?php

namespace humhub\modules\calendar_extension\models;

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
            [['timezone'], 'string', 'max' => 60],
            [['color'], 'string', 'max' => 7],
        ];
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
            'timezone' => Yii::t('CalendarExtensionModule.base', 'Timezone'),
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
//    public function getCalendarExtensionEvents()
//    {
//        return $this->hasMany(CalendarExtensionEvent::className(), ['calendar_id' => 'id']);
//    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCalendarExtensionCalendarEntries()
    {
        return $this->hasMany(CalendarExtensionCalendarEntry::className(), ['calendar_id' => 'id']);
    }
}
