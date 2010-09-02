<div id="sf_news">
<?php foreach($news as $aNews): ?>
    (<?php echo $aNews->getCreatedAt()->format('d F Y'); ?>) <strong><?php echo $aNews->getTitle(); ?></strong>
    <p><?php echo $aNews->getContent(); ?></p>
<?php endforeach; ?>
</div>