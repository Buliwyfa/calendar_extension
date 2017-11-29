<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use humhub\widgets\ModalButton;

/* @var $this yii\web\View */
/* @var $model humhub\modules\calendar_extension\models\CalendarExtensionCalendar */

?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h1><?= Yii::t('CalendarExtensionModule.base', 'Error') ?></h1>
    </div>
    <div class="panel-body">
        <div class="errorMessage">
            <?= Yii::t('CalendarExtensionModule.base', 'Calendar Module not found') ?>
            <?= $message ?>
        </div>
    </div>
