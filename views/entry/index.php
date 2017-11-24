<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('base', 'Calendar Extension Calendar Entries');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="panel-body">

    <p>
        <?= Html::a(Yii::t('base', 'Create Calendar Extension Calendar Entry'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'calendar_id',
            'title',
            'description:ntext',
            'start_datetime',
            // 'end_datetime',
            // 'all_day',
            // 'recur',
            // 'recur_type',
            // 'recur_interval',
            // 'recur_end',
            // 'color',
            // 'time_zone',
            // 'closed',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    </div>
</div>
