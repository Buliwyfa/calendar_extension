<?php

namespace humhub\modules\calendar_extension;

use humhub\modules\calendar_extension\integration\calendar\CalendarExtension;
use humhub\modules\calendar_extension\models\CalendarExtensionCalendarEntry;
use humhub\modules\calendar_extension\models\CalendarExtensionCalendar;
use humhub\modules\calendar_extension\SyncUtils;
use ICal\ICal;
use humhub\modules\calendar_extension\CalendarUtils;
use humhub\modules\content\models\Content;
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
//            echo '<pre>';
//            print_r($event);
//            echo '</pre>';
//            die();
            CalendarExtension::addItemTypes($event);
//            $event->addType(CalendarExtensionCalendarEntry::findAll());
        }
    }


    public static function onFindCalendarItems($event)
    {
        $contentContainer = $event->contentContainer;

        if(!$contentContainer || $contentContainer->isModuleEnabled('calendar_extension')) {
            /* @var $entry Entry[] */
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

    public static function onCron()
    {
        $calendarModels = CalendarExtensionCalendar::find()->all();
        foreach ($calendarModels as $calendarModel) {
            if ($calendarModel) {
                $ical = SyncUtils::createICal($calendarModel->url);
                if (!$ical) {
                    return;
                }

                // add info to CalendarModel
                $calendarModel->addAttributes($ical);
                $calendarModel->save();

                // check events
                if ($ical->hasEvents()) {
                    // get formatted array
                    $events = $ical->events();

                    // create Entry-models without safe
                    $models = SyncUtils::getModels($events, $calendarModel);
                    $result = SyncUtils::checkAndSubmitModels($models, $calendarModel->id);
                    if (!$result) {
                        return;
                    }
                }
            } else {
                return;
            }
        }
        return true;
    }

}

