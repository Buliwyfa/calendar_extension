<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use humhub\widgets\ModalButton;

/* @var $this yii\web\View */
/* @var $model humhub\modules\calendar_extension\models\CalendarExtensionCalendar */

$this->title = $model->title;
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h1><?= Yii::t('CalendarExtensionModule.views_calendar', 'Calendar: {modelClass}', ['modelClass' => Html::encode($this->title),
            ]) ?></h1>
    </div>
    <div class="panel-body">

        <div class="btn-group-sm">
            <?= Html::a(Yii::t('CalendarExtensionModule.base', 'Update'), $contentContainer->createUrl('/calendar_extension/calendar/update', ['id' => $model->id]), ['class' => 'btn btn-primary']) ?>
            <?= Html::a(Yii::t('CalendarExtensionModule.base', 'Delete'), $contentContainer->createUrl('/calendar_extension/calendar/delete', ['id' => $model->id]), [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('CalendarExtensionModule.base', 'Are you sure you want to delete this item?'),
                    'method' => 'post',
                ],
            ]) ?>
            <?= ModalButton::primary(Yii::t('CalendarExtensionModule.views_calendar', 'Sync Calendar'))->post($contentContainer->createUrl('/calendar_extension/calendar/sync', ['id' => $model->id]))->sm()->icon('fa-refresh')->right();?>
        </div>

        </br>
        <div>
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    //            'id',
                    'title',
                    'url:ntext',
                    'public:boolean',
                    'autosync:boolean',
                    'time_zone',
//                    'color',
                    'version',
                    'cal_name',
                    'cal_scale'
                ],
            ]) ?>
        </div>
        </br>
        <div >
            <?= Html::a(Yii::t('CalendarExtensionModule.base', 'Back to overview'), $contentContainer->createUrl('/calendar_extension/calendar/'), ['class' => 'btn btn-default']) ?>
        </div>

    </div>
</div>
