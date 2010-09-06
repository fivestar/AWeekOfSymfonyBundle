<p>Last translated at: <?php echo $entry->isOriginal() ? 'in original' : $entry->getUpdatedAt()->format(\DateTime::RFC2822) ?></p>

<textarea rows="10" cols="100" readonly="readonly"><?php echo $markdown ?></textarea>

<div class="markdown">
<?php echo $view['markdown']->transform($markdownSafe) ?>
</div>
