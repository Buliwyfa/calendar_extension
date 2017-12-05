<?php


use humhub\modules\dashboard\widgets\Sidebar;
use humhub\commands\CronController;

return [
	'id' => 'calendar_extension',
	'class' => 'humhub\modules\calendar_extension\Module',
	'namespace' => 'humhub\modules\calendar_extension',
	'events' => [
        ['class' => Sidebar::className(), 'event' => Sidebar::EVENT_INIT, 'callback' => ['humhub\modules\calendar_extension\Module', 'onDashboardSidebarInit']],
        ['class' => 'humhub\modules\calendar\interfaces\CalendarService', 'event' => 'getItemTypes', 'callback' => ['humhub\modules\calendar_extension\Events', 'onGetCalendarItemTypes']],
        ['class' => 'humhub\modules\calendar\interfaces\CalendarService', 'event' => 'findItems', 'callback' => ['humhub\modules\calendar_extension\Events', 'onFindCalendarItems']],
		['class' => CronController::className(), 'event' => CronController::EVENT_ON_HOURLY_RUN, 'callback' => ['humhub\modules\calendar_extension\Events', 'onCron']]
	],
];
?>

