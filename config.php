<?php


use humhub\modules\dashboard\widgets\Sidebar;
use humhub\modules\space\widgets\Menu;

return [
	'id' => 'calendar_extension',
	'class' => 'humhub\modules\calendar_extension\Module',
	'namespace' => 'humhub\modules\calendar_extension',
	'events' => [
//        ['class' => Menu::className(), 'event' => Menu::EVENT_INIT, 'callback' => ['humhub\modules\calendar_extension\Events', 'onSpaceMenuInit']],
        ['class' => Sidebar::className(), 'event' => Sidebar::EVENT_INIT, 'callback' => ['humhub\modules\calendar_extension\Module', 'onDashboardSidebarInit']],
        ['class' => 'humhub\modules\calendar\interfaces\CalendarService', 'event' => 'getItemTypes', 'callback' => ['humhub\modules\calendar_extension\Events', 'onGetCalendarItemTypes']],
        ['class' => 'humhub\modules\calendar\interfaces\CalendarService', 'event' => 'findItems', 'callback' => ['humhub\modules\calendar_extension\Events', 'onFindCalendarItems']],
	],
];
?>

