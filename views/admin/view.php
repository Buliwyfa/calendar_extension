<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model humhub\modules\calendar_extension\models\CalendarExtensionEntry */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('base', 'Calendar Extension Entries'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="panel-body">

        <!--    <p>
        <?/*= Html::a(Yii::t('base', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) */?>
        <?/*= Html::a(Yii::t('base', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('base', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) */?>
    </p>-->

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
//                'id',
                'organizer',
//                'last_modified',
//                'dtstamp',
                'title',
                'description:ntext',
                'start_datetime',
                'end_datetime',
//                'all_day',
//                'uid',
            ],
        ]) ?>

    </div>
</div>
