<?php

use yii\helpers\Html;
use humhub\widgets\ModalDialog;
use humhub\widgets\ActiveForm;
use kartik\widgets\DateTimePicker;
use humhub\widgets\ModalButton;

/* @var $this yii\web\View */
/* @var $model humhub\modules\calendar_extension\models\CalendarExtensionCalendarEntry */
/* @var $editUrl string */
/* @var $contentContainer \humhub\modules\content\models\ContentContainer */

$header = Yii::t('CalendarExtensionModule.base', 'Update {modelClass}: ', [
        'modelClass' => Yii::t('CalendarExtensionModule.base', 'External Calendar Entry')
    ]) . $model->title;
$model->calendar->color = empty($model->calendar->color) ? $this->theme->variable('info') : $model->calendar->color;

?>
<?php ModalDialog::begin(['header' => $header, 'closable' => false]) ?>
<?php $form = ActiveForm::begin(['enableClientValidation' => false]); ?>

<div class="modal-body">

    <?= $form->field($model, 'uid')->textInput(['maxlength' => true, 'disabled' => true]) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'location')->textarea(['maxlength' => true]) ?>

    <?= $form->field($model, 'start_datetime')->widget(DateTimePicker::className(), ['type' => DateTimePicker::TYPE_COMPONENT_APPEND, 'pluginOptions' => ['format' => 'yyyy-mm-dd hh:ii:ss', 'autoclose' => false]]) ?>

    <?= $form->field($model, 'end_datetime')->widget(DateTimePicker::className(), ['type' => DateTimePicker::TYPE_COMPONENT_APPEND, 'pluginOptions' => ['format' => 'yyyy-mm-dd hh:ii:ss', 'autoclose' => false]]) ?>

    <?= $form->field($model, 'all_day')->checkbox() ?>
</div>

<hr>

<div class="modal-footer">
    <?= ModalButton::submitModal($editUrl); ?>
    <?= ModalButton::cancel(); ?>
</div>
<?php ActiveForm::end(); ?>
<?php ModalDialog::end() ?>
