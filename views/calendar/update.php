<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model humhub\modules\space_news\models\SpaceNews */
$this->title = Yii::t('SpaceNewsModule.base', 'Update {modelClass}: ', [
        'modelClass' => Yii::t('SpaceNewsModule.base', 'Space News'),
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
