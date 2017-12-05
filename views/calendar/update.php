<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model humhub\modules\space_news\models\SpaceNews */
$this->title = Yii::t('CalendarExtensionModule.views_calendar', 'Update {modelClass}: ', [
        'modelClass' => Yii::t('CalendarExtensionModule.views_calendar', 'Calendar'),
    ]) . $model->id;
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
