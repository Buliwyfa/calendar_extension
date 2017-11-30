<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2017 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 *
 */

/**
 * Created by PhpStorm.
 * User: buddha
 * Date: 21.07.2017
 * Time: 17:28
 */

namespace humhub\modules\calendar_extension\widgets;

use Yii;
use humhub\modules\calendar_extension\models\CalendarExtensionCalendarEntry;
use humhub\modules\content\widgets\WallEntryControlLink;

class EditLink extends WallEntryControlLink
{
    /**
     * @var CalendarExtensionCalendarEntry
     */
    public $entry;

    public function init()
    {
        $this->label = Yii::t('ContentModule.widgets_views_editLink', 'Edit');
        $this->icon = 'fa-pencil';

        $this->options = [
            'data-action-click' => 'calendar.editModal',
            'data-action-target' =>"[data-content-key='".$this->entry->content->id."']",
            'data-action-url' => $this->entry->content->container->createUrl('/calendar_extension/entry/update', ['id' => $this->entry->id, 'cal' => 1])
        ];

        parent::init();
    }

}