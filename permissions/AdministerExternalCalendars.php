<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\calendar_extension\permissions;

use humhub\modules\space\models\Space;
use humhub\libs\BasePermission;

/**
 * Page Administration Permission
 */
class AdministerExternalCalendars extends BasePermission
{

    /**
     * @inheritdoc
     */
    public $defaultAllowedGroups = [
        Space::USERGROUP_OWNER,
        Space::USERGROUP_ADMIN,
        Space::USERGROUP_MODERATOR,
    ];
    
    /**
     * @inheritdoc
     */
    protected $fixedGroups = [
        Space::USERGROUP_USER
    ];

    /**
     * @inheritdoc
     */
    protected $title = "Administer external Calendars";

    /**
     * @inheritdoc
     */
    protected $description = "Allows the user to administer external calendars (rename, delete, add)";

    /**
     * @inheritdoc
     */
    protected $moduleId = 'calendar_extension';

}
