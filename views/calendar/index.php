<?php

use yii\helpers\Html;
use humhub\modules\space\models\Space;
use humhub\modules\user\models\User;

/* @var $this yii\web\View */
/* @var $models []\humhub\modules\calendar_extension\models\CalendarExtensionCalendar */

$this->title = Yii::t('CalendarExtensionModule.base', 'External Calendars');
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="panel-body">
        <div class="btn-group-sm">
            <?= Html::a('<i class="fa fa-pencil-square-o edit"></i> ' . Yii::t('CalendarExtensionModule.base', 'Add Calendar'), $contentContainer->createUrl('/calendar_extension/calendar/create'), ['class' => 'btn-sm btn-success']) ?>
        </div>
        <div>
            <table class="table table-responsive">
                <thead>
                <tr>
                    <th scope="col"><?= Yii::t('CalendarExtensionModule.base', 'ID'); ?></th>
                    <th scope="col"><?= Yii::t('CalendarExtensionModule.base', 'Title'); ?></th>
                    <th scope="col"><?= Yii::t('CalendarExtensionModule.base', 'Actions'); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($models as $model): ?>
                    <tr>
                        <td><?= $model->id; ?></td>
                        <td><?= $model->title; ?></td>
                        <td>
                            <?= Html::a('<i class="fa fa-eye view"></i> ' . Yii::t('CalendarExtensionModule.base', 'View'), $contentContainer->createUrl('/calendar_extension/calendar/view', ['id' => $model->id]), ['class' => 'btn-sm btn-info']) ?>
                            <?= Html::a('<i class="fa fa-pencil-square-o edit"></i> ' . Yii::t('CalendarExtensionModule.base', 'Update'), $contentContainer->createUrl('/calendar_extension/calendar/update', ['id' => $model->id]), ['class' => 'btn-sm btn-primary']) ?>
                            <?= Html::a('<i class="fa fa-trash-o delete"></i> ' . Yii::t('CalendarExtensionModule.base', 'Delete'), $contentContainer->createUrl('/calendar_extension/calendar/delete', ['id' => $model->id]), [
                                'class' => 'btn-sm btn-danger',
                                'data' => [
                                    'confirm' => Yii::t('CalendarExtensionModule.base', 'Are you sure you want to delete this item?'),
                                    'method' => 'post',
                                ],
                            ]) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        </br>
        <div >
            <?php
                if($contentContainer instanceof Space) {
                    $configUrl = $contentContainer->createUrl('/space/manage/module');
                }
                elseif ($contentContainer instanceof User) {
                    $configUrl = $contentContainer->createUrl('/user/account/edit-modules');
                }
                else {
                    $configUrl = '';
                }
            ?>
            <?= Html::a(Yii::t('CalendarExtensionModule.base', 'Back to overview'), $configUrl, ['class' => 'btn btn-sm btn-default']) ?>
        </div>

    </div>
</div>