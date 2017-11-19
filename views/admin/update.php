<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model humhub\modules\calendar_extension\models\CalendarExtensionEntry */

$this->title = Yii::t('base', 'Update {modelClass}: ', [
    'modelClass' => 'Calendar Extension Entry',
]) . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('base', 'Calendar Extension Entries'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('base', 'Update');
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="panel-body">
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>
</div>
