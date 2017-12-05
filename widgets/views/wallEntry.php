<?php
use humhub\libs\Helpers;
use humhub\widgets\MarkdownView;
use humhub\widgets\Button;
use yii\helpers\Html;
use humhub\modules\calendar_extension\models\CalendarExtensionCalendarEntry;

/* @var $calendarEntry CalendarExtensionCalendarEntry */
/* @var $stream boolean */
/* @var $collapse boolean */

$color = $calendarEntry->calendar->color ? $calendarEntry->calendar->color : $this->theme->variable('info');
?>
<div class="media event">
    <div class="y" style="padding-left:10px; border-left: 3px solid <?= $color ?>">
        <div class="media-body clearfix">
            <a href="<?= $calendarEntry->getUrl(); ?>" class="pull-left" style="margin-right: 10px">
                <i class="fa fa-calendar colorDefault" style="font-size: 35px;"></i>
            </a>
            <h4 class="media-heading">
                <a href="<?= $calendarEntry->getUrl(); ?>">
                    <b><?= Html::encode($calendarEntry->title); ?></b>
                </a>
            </h4>
            <h5>
                <?= $calendarEntry->getFormattedTime() ?>
            </h5>
        </div>
        <?php if (!empty($calendarEntry->description) || !empty($calendarEntry->location) || !empty($calendarEntry->calendar->title) ) : ?>
            <div <?= ($collapse) ? 'data-ui-show-more' : '' ?> data-read-more-text="<?= Yii::t('CalendarExtensionModule.views_entry_view', "Read full description...") ?>" style="overflow:hidden">
                <?= MarkdownView::widget(['markdown' => $calendarEntry->description]); ?>
                <?php if (!empty($calendarEntry->location)) : ?>
                    <i class="fa fa-map-marker colorDefault pull-left" style="font-size: 20px; margin-right: 8px"></i>
                    <?= MarkdownView::widget(['markdown' => $calendarEntry->location]); ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
