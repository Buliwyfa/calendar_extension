<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2017 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 *
 */
use humhub\widgets\ModalButton;
use humhub\widgets\ModalDialog;


?>

<?php ModalDialog::begin(['size' => 'large', 'closable' => true]); ?>
    <div class="modal-body" style="padding-bottom:0px">
        <?= $this->renderAjax('error', ['message' => $message])?>
    </div>
    <div class="modal-footer">
        <?= ModalButton::cancel(Yii::t('CalendarExtensionModule.base', 'Close')) ?>
    </div>
<?php ModalDialog::end(); ?>