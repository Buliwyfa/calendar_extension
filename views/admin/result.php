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
/* @var $meeting \humhub\modules\meeting\models\Meeting  */
/* @var $canManageEntries boolean  */
/* @var $editUrl string  */

?>

<?php ModalDialog::begin(['size' => 'large', 'closable' => true]); ?>
    <div class="modal-body" style="padding-bottom:0px">
        <?= $message?>
    </div>
    <div class="modal-footer">
        <?= ModalButton::cancel(Yii::t('CalendarModule.base', 'Close')) ?>
    </div>
<?php ModalDialog::end(); ?>