<?php $view->extend('AWeekOfSymfonyBundle::layout') ?>

<article>

<h2>Recently "A Week of symfony" entries</h2>

<?php if (count($entries) > 0): ?>
<ul>
<?php foreach ($entries as $entry): ?>
    <li>
        <span><a href="<?php echo $entry->getUri() ?>" target="_blank"><?php echo $entry->getSubject() ?></a></span>
        <span>
        [<a href="<?php echo $view['entry_router']->generate('awos_show', $entry, array('_format' => 'markdown')) ?>" target="_blank">Show as markdown</a>]
        [<a href="<?php echo $view['entry_router']->generate('awos_edit', $entry) ?>" target="_blank">Translate</a>]
        </span>
    </li>
<?php endforeach; ?>
</li>
<?php endif; ?>

</article>
