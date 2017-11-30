<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2017 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 *
 */
use humhub\widgets\Button;
use humhub\widgets\ModalButton;
use humhub\widgets\ModalDialog;

/* @var $this \humhub\components\View */
/* @var $module \humhub\modules\calendar_extension\models\CalendarExtensionCalendar  */
/* @var $canManageEntries boolean  */
/* @var $editUrl string  */

?>

<?php ModalDialog::begin(['size' => 'large', 'closable' => true]); ?>
    <div class="modal-body" style="padding-bottom:0px">
        <div class="text-center">
            <?= $message?>
        </div>
    </div>
    <div class="modal-footer">
        <?= ModalButton::cancel(Yii::t('CalendarExtensionModule.base', 'Close')) ?>
    </div>
<?php ModalDialog::end(); ?>