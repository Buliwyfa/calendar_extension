<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model humhub\modules\calendar_extension\models\CalendarExtensionCalendarEntry */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('CalendarExtensionModule.base', 'Calendar Extension Calendar Entries'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel panel-default">
    <div class="panel-heading text-center">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="panel-body">

        <p>
            <?= Html::a(Yii::t('base', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a(Yii::t('base', 'Delete'), ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('base', 'Are you sure you want to delete this item?'),
                    'method' => 'post',
                ],
            ]) ?>
        </p>

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
//                'id',
//                'calendar.title',
                [
                    'attribute' => 'calendar.title',
                    'label' => Yii::t('CalendarExtensionModule.base', 'Calendar'),
//                    'value' => ((isset($model->description)) ? $model->description : '' ),
                ],
                'title',
//                'description:ntext',
                [
                    'attribute' => 'description',
                    'value' => ((isset($model->description)) ? $model->description : '' ),
                ],
                [
                    'attribute' => 'start_datetime',
                    'value' => (($model->all_day ==0) ? Yii::$app->formatter->asDatetime($model->start_datetime) : Yii::$app->formatter->asDate($model->start_datetime) ),
                ],
                [
                    'attribute' => 'end_datetime',
                    'value' => (($model->all_day ==0) ? Yii::$app->formatter->asDatetime($model->end_datetime) : Yii::$app->formatter->asDate($model->end_datetime) ),
                ],
//                'start_datetime:datetime',
//                'end_datetime:datetime',
//            'all_day:boolean',
                [
                    'attribute' => 'all_day',
                    'value' => (($model->all_day ==0) ? '' : Yii::t('CalendarExtensionModule.base', 'Yes') ),
                ],
                'Location',
//            'time_zone',
            ],
            // only show items that are set
            'template' => function($attribute, $index, $widget){
                //your code for rendering here. e.g.
                if($attribute['value'])
                {
                    return "<tr><th>{$attribute['label']}</th><td>{$attribute['value']}</td></tr>";
                }
            },
        ]) ?>
    </div>

</div>
