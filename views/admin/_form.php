<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model humhub\modules\calendar_extension\models\CalendarExtensionEntry */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="calendar-extension-entry-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'organizer')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'last_modified')->textInput() ?>

    <?= $form->field($model, 'dtstamp')->textInput() ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'start_datetime')->textInput() ?>

    <?= $form->field($model, 'end_datetime')->textInput() ?>

    <?= $form->field($model, 'all_day')->textInput() ?>

    <?= $form->field($model, 'uid')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('base', 'Create') : Yii::t('base', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
