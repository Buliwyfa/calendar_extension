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
//    public $managePermission = ManageCalendar::class;

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

//    public function getContextMenu()
//    {
//        $canEdit = $this->contentObject->content->canEdit();
//        if($canEdit) {
//            $this->controlsOptions = [
//                'add' => [
//                    [CloseLink::class, ['calendar' => $this->contentObject], ['sortOrder' => 210]]
//                ]
//            ];
//        }
//
//        if($this->stream) {
//            return parent::getContextMenu();
//        }
//
//        $this->controlsOptions['prevent'] = [\humhub\modules\content\widgets\EditLink::class , \humhub\modules\content\widgets\DeleteLink::class];
//        $result = parent::getContextMenu();
//
//        if($canEdit) {
//            $this->addControl($result, [DeleteLink::class, ['calendar' => $this->contentObject], ['sortOrder' => 100]]);
//            $this->addControl($result, [EditLink::class, ['calendar' => $this->contentObject], ['sortOrder' => 200]]);
//        }
//
//        return $result;
//    }

//    public function getWallEntryViewParams()
//    {
//        $params = parent::getWallEntryViewParams();
//        if($this->isInModal()) {
//            $params['showContentContainer'] = true;
//        }
//        return $params;
//    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        Assets::register($this->getView());

//        return $this->render('wallEntry', array('entry' => $this->contentObject, 'content' => $revision->content, 'justEdited' => $this->justEdited));
        return $this->render('wallEntryCalendar', [
            'calendar' => $this->contentObject,
            'collapse' => $this->collapse,
            'contentContainer' => $this->contentObject->content->container
        ]);
    }

}
