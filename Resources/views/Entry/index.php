<?php $view->extend('AWeekOfSymfonyBundle::layout') ?>

<article>

<h2>Recently "A Week of symfony" entries</h2>

<?php if (count($entries) > 0): ?>
<ul>
<?php foreach ($entries as $entry): ?>
    <li>
        <a href="<?php echo $view['router']->generate('awos_show', $entry['route']->getRawValue()) ?>"><?php echo $entry['content'] ?></a>
        [<a href="<?php echo $view['router']->generate('awos_show', array_merge($entry['route']->getRawValue(), array('_format' => 'markdown'))) ?>" target="_blank">Show as markdown</a>]
        [<a href="<?php echo $entry['uri'] ?>" target="_blank">Read in the original</a>]
    </li>
<?php endforeach; ?>
</li>
<?php endif; ?>

</article>
