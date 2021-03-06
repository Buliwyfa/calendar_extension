<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2017 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 *
 */
use humhub\widgets\ModalButton;
use humhub\widgets\ModalDialog;

/* @var $this \humhub\components\View */
/* @var $model \humhub\modules\calendar_extension\models\CalendarExtensionCalendarEntry  */
/* @var $canManageEntries boolean  */
/* @var $editUrl string  */

$deleteUrl = $contentContainer->createUrl('/calendar_extension/entry/delete', ['id' => $model->id, 'cal' => 1]);
?>

<?php ModalDialog::begin(['size' => 'large', 'closable' => true]); ?>
    <div class="modal-body" style="padding-bottom:0px">
        <?= $this->renderAjax('view', ['model' => $model, 'stream' => false])?>
    </div>
    <div class="modal-footer">
        <?php if($canManageEntries): ?>
            <?= ModalButton::primary(Yii::t('CalendarExtensionModule.base', 'Edit'))->load($editUrl)->loader(true); ?>
        <?php endif; ?>
        <?= ModalButton::cancel(Yii::t('CalendarExtensionModule.base', 'Close')) ?>
    </div>
<?php ModalDialog::end(); ?>