<?php
use humhub\libs\Helpers;
use humhub\widgets\MarkdownView;
?>
<div class="media meeting">
    <div class="media-body">
        <h4 class="media-heading"><a href="<?php echo $entry->getUrl(); ?>"><?php echo $entry->title; ?></a></h4>
        <div class="markdown-render">
            <?php echo MarkdownView::widget(['markdown' => Helpers::truncateText($content, 500), 'parserClass' => "humhub\modules\calendar_extension\Markdown"]); ?>
        </div>

        <a href="<?php echo $entry->getUrl(); ?>"><?php echo Yii::t('CalendarExtensionModule.widgets_views_wallentry', 'Open Calendar Entry...'); ?></a>
    </div>
</div>
