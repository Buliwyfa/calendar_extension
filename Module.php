<?php

namespace humhub\modules\calendar_extension;

use Yii;
use yii\helpers\Url;

class Module extends \humhub\components\Module
{

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


}