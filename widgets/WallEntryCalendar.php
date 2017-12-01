<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\calendar_extension\widgets;

use humhub\modules\calendar_extension\assets\Assets;
use humhub\modules\calendar_extension\permissions\ManageCalendar;
use Yii;

/**
 * @inheritdoc
 */
class WallEntryCalendar extends \humhub\modules\content\widgets\WallEntry
{
    /**
     * @var string
     */
    public $managePermission = ManageCalendar::class;

    /**
     * @inheritdoc
     */
//    public $editRoute = "/calendar_extension/calendar/update";

    /**
     * @inheritdoc
     */
//    public $editMode = self::EDIT_MODE_MODAL;

    /**
     * @var bool defines if the description and participation info should be cut at a certain height, this should only be the case in the stream
     */
    public $stream = true;

    /**
     * @var bool defines if the content should be collapsed
     */
    public $collapse = true;

    /**
     * @inheritdoc
     */
    public $showFiles = false;

    /**
     * @inheritdoc
     */
    public function run()
    {
        Assets::register($this->getView());

        return $this->render('wallEntryCalendar', [
            'calendar' => $this->contentObject,
            'collapse' => $this->collapse,
            'contentContainer' => $this->contentObject->content->container
        ]);
    }

}
