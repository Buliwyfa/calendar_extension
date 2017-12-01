<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\calendar_extension\widgets;

use humhub\modules\calendar_extension\assets\Assets;
use humhub\modules\calendar_extension\permissions\ManageEntry;
use Yii;

/**
 * @inheritdoc
 */
class WallEntry extends \humhub\modules\content\widgets\WallEntry
{
    /**
     * @var string
     */
    public $managePermission = ManageEntry::class;

    /**
     * @inheritdoc
     */
    public $editRoute = "/calendar_extension/entry/update";

    /**
     * @inheritdoc
     */
    public $editMode = self::EDIT_MODE_MODAL;

    /**
     * @var bool defines if the description and location info should be cut at a certain height, this should only be the case in the stream
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
//                    [CloseLink::class, ['entry' => $this->contentObject], ['sortOrder' => 210]]
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
//            $this->addControl($result, [DeleteLink::class, ['entry' => $this->contentObject], ['sortOrder' => 100]]);
//            $this->addControl($result, [EditLink::class, ['entry' => $this->contentObject], ['sortOrder' => 200]]);
//        }
//
//        return $result;
//    }

    public function getWallEntryViewParams()
    {
        $params = parent::getWallEntryViewParams();
        if($this->isInModal()) {
            $params['showContentContainer'] = true;
        }
        return $params;
    }

    public function isInModal()
    {
        return Yii::$app->request->get('cal');
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        Assets::register($this->getView());
        $entry = $this->contentObject;
        
        return $this->render('wallEntry', [
            'calendarEntry' => $this->contentObject,
            'collapse' => $this->collapse,
            'contentContainer' => $entry->content->container
        ]);
    }

}
