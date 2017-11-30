<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2016 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\calendar_extension\permissions;

use humhub\modules\space\models\Space;
use humhub\modules\user\models\User;
use Yii;

/**
 * CreateEntry Permission
 */
class CreateEntry extends \humhub\libs\BasePermission
{
    /**
     * @inheritdoc
     */
    protected $moduleId = 'calendar_extension';

    /**
     * @inheritdoc
     */
    public $defaultAllowedGroups = [
        Space::USERGROUP_OWNER,
        Space::USERGROUP_ADMIN,
        Space::USERGROUP_MODERATOR,
        User::USERGROUP_SELF
    ];

    /**
     * @inheritdoc
     */
    protected $fixedGroups = [
        Space::USERGROUP_USER,
        User::USERGROUP_FRIEND,
        User::USERGROUP_GUEST,
        User::USERGROUP_USER,
        User::USERGROUP_FRIEND,
    ];

    public function getTitle()
    {
        return Yii::t('CalendarExtensionModule.permissions', 'Create entry');
    }

    public function getDescription()
    {
        return Yii::t('CalendarExtensionModule.permissions', 'Allows the user to create new external calendar entries');
    }

}
