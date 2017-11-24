<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2017 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 *
 */

namespace humhub\modules\calendar_extension\integration\calendar;

use humhub\modules\calendar_extension\models\CalendarExtensionCalendarEntryType;
use Yii;
use humhub\libs\Html;
use yii\base\Object;
use yii\helpers\Url;
use DateTime;
use DateInterval;
use humhub\modules\calendar_extension\models\CalendarExtensionCalendarEntry;
use humhub\modules\calendar_extension\models\CalendarExtensionCalendarEntryQuery;

/**
 * Created by PhpStorm.
 * User: David Born
 * Date: 18.11.2017
 * Time: 15:19
 */

class CalendarExtension extends Object
{
    /**
     * Default color of external calendar type items.
     */
    const DEFAULT_COLOR = '#DC0E25';

    const ITEM_TYPE_KEY = 'calendar_extension';

    /**
     * @param $event \humhub\modules\calendar\interfaces\CalendarItemTypesEvent
     * @return mixed
     */
    public static function addItemTypes($event)
    {
        /* @var $meetings Meeting[] */
        $event->addType(static::ITEM_TYPE_KEY, [
            'title' => Yii::t('CalendarExtensionModule.base', 'Entry'),
            'color' => static::DEFAULT_COLOR,
            'icon' => 'fa-calendar-o',
            'format' => 'Y-m-d',
        ]);
    }

    /**
     * @param $event \humhub\modules\calendar\interfaces\CalendarItemsEvent
     */
    public static function addItems($event)
    {
        /* @var $meetings Meeting[] */
        $entries = CalendarExtensionCalendarEntryQuery::findForEvent($event);

        $items = [];
        foreach ($entries as $entry) {
            $items[] = $entry->getFullCalendarArray();
        }

        $event->addItems(static::ITEM_TYPE_KEY, $items);
    }

}