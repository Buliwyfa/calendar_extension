<?php

namespace humhub\modules\calendar_extension;

use humhub\components\ModuleManager;
use humhub\modules\calendar_extension\permissions\ManageCalendar;
use humhub\modules\calendar_extension\models\CalendarExtensionCalendar;
use humhub\modules\calendar_extension\models\CalendarExtensionCalendarEntry;
use humhub\modules\calendar_extension\models\CalendarExtensionEntry;
use humhub\modules\calendar\interfaces\CalendarService;
use humhub\modules\content\models\ContentContainer;
use Yii;
use yii\helpers\Url;
use Zend\Uri\Http;

use humhub\modules\space\models\Space;
use humhub\modules\user\models\User;
use humhub\modules\content\components\ContentContainerModule;
use humhub\modules\content\components\ContentContainerActiveRecord;



class Module extends ContentContainerModule
{

    /**
     * @inheritdoc
     */
//    public function init()
//    {
//        parent::init();
//        $this->set(CalendarService::class, ['class' => CalendarService::class]);
//    }

    /**
     * @inheritdoc
     */
    public function getContentContainerTypes()
    {
        return [
            Space::className(),
            User::className(),
        ];
    }

    public function enable()
    {
        // check if calendar module is enabled
        if (!Yii::$app->hasModule('calendar') && !isset(Yii::$app->modules['calendar']))
        {
            return;
        }
        return parent::enable(); // TODO: Change the autogenerated stub
    }

    /**
     * @inheritdoc
     */
    public function disable()
    {
//        foreach (CalendarExtensionCalendarEntry::find()->all() as $entry) {
//            $entry->delete();
//        }
        foreach (CalendarExtensionCalendar::find()->all() as $entry) {
            $entry->delete();
        }
        parent::disable();
    }

    /**
     * @inheritdoc
     */
    public function getContentContainerName(ContentContainerActiveRecord $container)
    {
        return Yii::t('CalendarExtensionModule.base', 'External Calendar');
    }

    /**
     * @inheritdoc
     */
    public function getContentContainerDescription(ContentContainerActiveRecord $container)
    {
        if ($container instanceof Space) {
            return Yii::t('CalendarExtensionModule.base', 'Manage external calendar here.');
        }elseif ($container instanceof User) {
            return Yii::t('CalendarExtensionModule.base', 'Manage external calendar for your profile and mainmenu.');
        }
    }

    /**
     * @inheritdoc
     */
    public function disableContentContainer(ContentContainerActiveRecord $container)
    {
        parent::disableContentContainer($container);
        foreach (CalendarExtensionCalendar::find()->contentContainer($container)->all() as $item) {
            $item->delete();
        }
    }

    /**
     * @inheritdoc
     */
    public $resourcesPath = 'resources';

    /**
     * On Init of Dashboard Sidebar, add the widget
     *
     * @param type $event
     */
    public static function onDashboardSidebarInit($event)
    {
        if (Yii::$app->user->isGuest) {
            return;
        }

        $module = Yii::$app->getModule('calendar_extension');
    }

    public function getConfigUrl()
    {
        return Url::to(['/calendar_extension/admin/index']);
    }

    public function getContentContainerConfigUrl(ContentContainerActiveRecord $container)
    {
        if ($container->permissionManager->can(ManageCalendar::class))
        {
            return $container->createUrl('/calendar_extension/calendar/index');
        }
        else
        {
            return;
        }
    }

    /**
     * @inheritdoc
     */
    public function getPermissions($contentContainer = null)
    {
        if ($contentContainer !== null) {
            return [
                new permissions\ManageCalendar(),
                new permissions\ManageEntry(),
            ];
        }
        return [];
    }

//    public function getNotifications()
//    {
//        return parent::getNotifications(); // TODO: Change the autogenerated stub
//    }

    /**
     * @inheritdoc
     */
//    public function beforeAction($action)
//    {
//        // Fix prior 1.2.1 without set formatter timeZone
//        // https://github.com/humhub/humhub/commit/3a06a3816131c3c10659b65e70422a8b8bdca15c#diff-6245cc1612ecb552c18a2e5a1d9bbca2c
//        if (empty(Yii::$app->formatter->timeZone)) {
//            Yii::$app->formatter->timeZone = Yii::$app->timeZone;
//        }
//        return parent::beforeAction($action);
//    }


}