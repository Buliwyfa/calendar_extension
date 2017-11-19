<?php

namespace humhub\modules\calendar_extension;

use humhub\modules\calendar_extension\integration\calendar\CalendarExtension;
use humhub\modules\calendar_extension\integration\calendar\CalendarExtensionQuery;
use Yii;
use yii\helpers\Url;
use yii\base\Object;

class Events extends Object
{

    /**
 * @param $event \humhub\modules\calendar\interfaces\CalendarItemTypesEvent
 * @return mixed
 */
    public static function onGetCalendarItemTypes($event)
    {
        $contentContainer = $event->contentContainer;

        if(!$contentContainer || $contentContainer->isModuleEnabled('calendar_extension')) {
            CalendarExtension::addItemTypes($event);
        }
    }


    public static function onFindCalendarItems($event)
    {
        $contentContainer = $event->contentContainer;

        if(!$contentContainer || $contentContainer->isModuleEnabled('calendar_extension')) {
            /* @var $entry Entry[] */
            $entry = CalendarExtension::addItems($event);
        }
    }


    /**
     * Defines what to do if admin menu is initialized.
     *
     * @param $event
     */
    public static function onAdminMenuInit($event)
    {
        $event->sender->addItem(array(
            'label' => "Calendar_extension",
            'url' => Url::to(['/calendar_extension/admin']),
            'group' => 'manage',
            'icon' => '<i class="fa fa-certificate" style="color: #6fdbe8;"></i>',
            'isActive' => (Yii::$app->controller->module && Yii::$app->controller->module->id == 'calendar_extension' && Yii::$app->controller->id == 'admin'),
            'sortOrder' => 99999,
        ));
    }

}

