<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('CalendarExtensionModule.base', 'External Calendars');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="panel-body">
        <div class="btn-group-sm">
            <?= Html::a(Yii::t('CalendarExtensionModule.base', 'Add Calendar'), ['create'], ['class' => 'btn btn-success']) ?>
        </div>
        <div>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    //                'id',
                    'title',
//                    'url:ntext',

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'contentOptions'=>['style'=>'min-width: 60px;'] // <-- right here

                    ],
                ],
//                'tableOptions' => [
//                    'class'=>'table'
//                ],
            ]);
            ?>
        </div>
        </br>
        <div >
            <?= Html::a(Yii::t('CalendarExtensionModule.base', 'Back to overview'), ['/admin/module'], ['class' => 'btn btn-sm btn-default']) ?>
        </div>

    </div>
</div>