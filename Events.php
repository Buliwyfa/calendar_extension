<?php

namespace humhub\modules\calendar_extension;

use Yii;
use yii\helpers\Url;
use yii\base\Object;
use humhub\modules\calendar_extension\models\CalendarExtensionCalendar;
use humhub\modules\calendar_extension\integration\calendar\CalendarExtension;

class Events extends Object
{

    /**
     * @param $event \humhub\modules\calendar\interfaces\CalendarItemTypesEvent
     * @return mixed
     */
    public static function onGetCalendarItemTypes($event)
    {
        $contentContainer = $event->contentContainer;

        if (!$contentContainer || $contentContainer->isModuleEnabled('calendar_extension')) {
            CalendarExtension::addItemTypes($event);
        }
    }


    /**
     * @param $event
     */
    public static function onFindCalendarItems($event)
    {
        $contentContainer = $event->contentContainer;

        if (!$contentContainer || $contentContainer->isModuleEnabled('calendar_extension')) {
            CalendarExtension::addItems($event);
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

    /**
     * Defines what to do if hourly cron is initialized.
     *
     * @param $event
     * @return bool|void
     */
    public static function onCron()
    {
        $calendarModels = CalendarExtensionCalendar::find()->all();

        foreach ($calendarModels as $calendarModel)
        {
            if ($calendarModel) {
                if (!$calendarModel->autosync)
                {
                    continue;
                }
                $ical = SyncUtils::createICal($calendarModel->url);
                if (!$ical)
                {
                    continue;
                }

                // add info to CalendarModel
                $calendarModel->addAttributes($ical);
                $calendarModel->save();

                // check events
                if ($ical->hasEvents())
                {
                    // get formatted array
                    $events = $ical->events();

                    // create Entry-models without safe
                    $models = SyncUtils::getModels($events, $calendarModel);
                    $result = SyncUtils::checkAndSubmitModels($models, $calendarModel->id);
                    if (!$result)
                    {
                        continue;
                    }
                }
            }
            else
            {
                continue;
            }
        }

        return;
    }
}

