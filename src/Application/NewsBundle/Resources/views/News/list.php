<?php foreach ($news as $aNews): ?>

    <div class="latest_news">
        <div class="header_04"><?php echo $aNews->getCreatedAtFormatted('%d %B %Y'); ?></div>
        <div class="header_02"><?php echo $aNews->getTitle(); ?></div>
        <p><?php echo $aNews->getContent(); ?></p>
    </div>

    <div class="margin_bottom_20"></div>
    
<?php endforeach; ?>