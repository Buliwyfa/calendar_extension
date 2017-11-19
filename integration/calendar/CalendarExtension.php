<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2017 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 *
 */

namespace humhub\modules\calendar_extension\integration\calendar;

use DateTime;
use humhub\modules\calendar\models\CalendarEntry;
use humhub\widgets\Label;
use Yii;
use yii\base\Object;
use yii\helpers\Url;
use humhub\modules\calendar_extension\models\CalendarExtensionEntry;

/**
 * Created by PhpStorm.
 * User: David Born
 * Date: 18.11.2017
 * Time: 15:19
 */

class CalendarExtension extends Object
{
    /**
     * Default color of meeting type calendar items.
     */
    const DEFAULT_COLOR = '#DC0E25';

    const ITEM_TYPE_KEY = 'calendar_extension';

    /**
     * @param $event \humhub\modules\calendar\interfaces\CalendarItemTypesEvent
     * @return mixed
     */
    public static function addItemTypes($event)
    {
        $event->addType(static::ITEM_TYPE_KEY, [
//            'title' => Yii::t('CalendarExtension.base', 'Meeting'),
            'title' => Yii::t('CalendarExtensionModule.base', 'Entry'),
            'color' => static::DEFAULT_COLOR,
            'icon' => 'fa-calendar-o'
        ]);
    }

    /**
     * @param $event \humhub\modules\calendar\interfaces\CalendarItemsEvent
     */
    public static function addItems($event)
    {
        /* @var $meetings Meeting[] */
        $meetings = CalendarExtensionQuery::findForEvent($event);

        $items = [];
        foreach ($meetings as $meeting) {
            $items[] = [
                'start' => $meeting->getStart_DateDateTime(),
                'end' => $meeting->getEnd_DateDateTime(),
                'title' => $meeting->title,
                'editable' => false,
                'icon' => 'fa-calendar-o',
                'viewUrl' => '/calendar_extension/admin/modal?id=' . $meeting->id,
                'openUrl' => '',
                'updateUrl' => '',
            ];
        }

        $event->addItems(static::ITEM_TYPE_KEY, $items);
    }

}