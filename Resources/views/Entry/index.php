<?php $view->extend('AWeekOfSymfonyBundle::layout') ?>

<article>

<h2>Recently "A week of symfony" entries</h2>

<?php if (count($entries) > 0): ?>
<table id="awos-entry-summary">
    <thead>
        <tr>
            <th>Original entry</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($entries as $entry): ?>
        <tr>
            <td>
                <a href="<?php echo $entry->getUri() ?>" target="_blank"><?php echo $entry->getSubject() ?></a>
            </td>
            <td>
                [<a href="<?php echo $view['entry_router']->generate('awos_edit', $entry) ?>">Translate</a>]
                [<a href="<?php echo $view['entry_router']->generate('awos_show', $entry, array('_format' => 'markdown', 'original' => '1')) ?>" target="_blank">Markdown</a>]
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>

</article>
