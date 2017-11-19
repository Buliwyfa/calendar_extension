<?php

use yii\helpers\Html;
use yii\grid\GridView;
use humhub\widgets\AjaxButton;
use yii\helpers\Url;
use humhub\widgets\ModalButton;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('base', 'Calendar Extension Entries');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="panel-body">
        <p>
            <?= Html::a(Yii::t('base', 'Create Calendar Extension Entry'), ['create'], ['class' => 'btn-sm btn btn-success']) ?>
            <?= ModalButton::success(Yii::t('CalendarExtensionModule.base', 'Update Link'))->post('/calendar_extension/admin/sync')->sm()->icon('fa-refresh')->right();?>
<!--            --><?php
//            echo AjaxButton::widget([
//                'label' => Yii::t('CalendarExtensionModule.base', 'Update Link'),
//                'ajaxOptions' => [
//                    'type' => 'POST',
//                    'success' => 'function(html){ $("#error").html(html); }',
//                    'url' => Url::to('/calendar_extension/admin/sync'),
//                ],
//                'htmlOptions' => [
//                    'class' => 'btn btn-danger'
//                ]
//            ]);
//            ?>
        </p>

        <?php
        // show results of the ical
//        if (isset($result) && $result != null) {
//            echo 'Updated / New iCal-Results:';
//        }
//        foreach ($result as $event) {
//            echo '<pre>';
//            print_r($event);
//            echo '</pre>';
//        }
//
//        // show updated / new stored models
//        if (isset($models) && $models != null) {
//            echo 'Updated / New Models:';
//        }
//        foreach ($models as $model) {
//            echo '<pre>';
//            print_r($model);
//            echo '</pre>';
//        }
//        ?>

        </br>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'id',
                'organizer',
                'last_modified',
                'dtstamp',
                'title',
                // 'description:ntext',
                // 'start_datetime',
                // 'end_datetime',
                // 'all_day',
                // 'uid',

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>
</div>