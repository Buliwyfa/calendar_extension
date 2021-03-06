<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use humhub\widgets\ColorPickerField;

/* @var $this yii\web\View */
/* @var $model humhub\modules\space_news\models\SpaceNews */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="calendar-extension-calendar-form">

    <?php $form = ActiveForm::begin(); ?>

    <div id="event-color-field" class="form-group space-color-chooser-edit" style="margin-top: 5px;">
        <?= $form->field($model, 'color')->widget(ColorPickerField::className(), ['container' => 'event-color-field'])->label(Yii::t('CalendarExtensionModule.views_calendar', 'Title and Color')); ?>

        <?= $form->field($model, 'title', ['template' => '
                                    {label}
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i></i>
                                        </span>
                                        {input}
                                    </div>
                                    {error}{hint}'
        ])->textInput(['placeholder' => Yii::t('CalendarExtensionModule.model_calendar', 'Title'), 'maxlength' => true])->label(false) ?>

    </div>

    <?= $form->field($model, 'url')->textarea(['rows' => 6]) ?>


    <?= $form->field($model, 'public')->checkbox() ?>
    <?= $form->field($model, 'autosync')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('CalendarExtensionModule.base', 'Save') : Yii::t('CalendarExtensionModule.base', 'Save'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

