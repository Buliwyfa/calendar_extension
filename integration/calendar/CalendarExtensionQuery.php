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
 * Date: 14.09.2017
 * Time: 20:44
 */

namespace humhub\modules\calendar_extension\integration\calendar;

use humhub\modules\calendar\interfaces\AbstractCalendarQuery;
use humhub\modules\calendar_extension\models\CalendarExtensionEntry;

class CalendarExtensionQuery extends AbstractCalendarQuery
{
    /**
     * @inheritdoc
     */
    protected static $recordClass = CalendarExtensionEntry::class;

    public $startField = 'start_datetime';
    public $endField = 'start_datetime';
    public $dateFormat = 'Y-m-d H:i:s';

    /**
     * @inheritdoc
     */
//    public function filterIsParticipant()
//    {
//        $this->_query->leftJoin('meeting_participant', 'meeting.id=meeting_participant.meeting_id AND meeting_participant.user_id=:userId', [':userId' => $this->_user->id]);
//        $this->_query->andWhere('meeting_participant.id IS NOT NULL');
//    }
}