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

$deleteUrl = '/calendar_extension/admin/delete?id=' . $meeting->id;
?>

<?php ModalDialog::begin(['size' => 'large', 'closable' => true]); ?>
    <div class="modal-body" style="padding-bottom:0px">
        <?= $this->renderAjax('view', ['model' => $meeting])?>
    </div>
    <div class="modal-footer">
        <?php if($canManageEntries): ?>
            <?= ModalButton::primary(Yii::t('CalendarModule.base', 'Edit'))->load($editUrl)->loader(true); ?>
        <?php endif; ?>
        <?= ModalButton::cancel(Yii::t('CalendarModule.base', 'Close')) ?>
    </div>
<?php ModalDialog::end(); ?>