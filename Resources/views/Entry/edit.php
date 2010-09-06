<?php $view->extend('AWeekOfSymfonyBundle::layout') ?>

<p><a href="<?php echo $view['router']->generate('awos') ?>">&lt;&lt; Back to list</a></p>

<article>

<h2><?php echo $originalEntry->getSubject() ?></h2>

<p><a href="<?php echo $originalEntry->getUri() ?>" target="_blank">&gt; Read in original</a></p>

<form action="<?php echo $view['entry_router']->generate('awos_translate', $entry) ?>" method="post" id="translate-form">

    <div class="origin"><?php echo $originalEntry->getRawValue()->getSummary() ?></div>
    <div>
        <textarea name="entry[summary]" rows="5" cols="80"><?php echo $markdownSummary ?></textarea>
    </div>

    <?php if ($entry->hasMailingList()): ?>
    <h3>開発メーリングリスト</h3>

    <div class="origin">
        <ul>
            <?php $originalML = $originalEntry->getMailinglist(); ?>
            <?php foreach ($entry->getMailinglist() as $i => $thread): ?>
            <li>
                <a href="<?php echo $thread->getUri() ?>" target="_blank"><?php echo $originalML[$i]->getSubject() ?></a>
                <br>
                <input type="text" name="entry[mailing_list][<?php echo $i ?>]" value="<?php echo $thread->getSubject() ?>" size="120">
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

    <h3>開発ハイライト</h3>

    <?php if ($entry->hasHighlights()): ?>
    <div class="origin">
        <?php foreach ($entry->getAllHighlights() as $name => $highlights): ?>
        <?php $originalHighlights = $originalEntry->getHighlights($name); ?>
        <h4><?php echo $highlights->getLabel(); ?></h4>

        <p>
            <?php if ($highlights->getChangeLogUri()): ?><a href="<?php echo $highlights->getChangeLogUri() ?>" target="_blank"><?php
                endif; ?>チェンジログ<?php if ($highlights->getChangeLogUri()): ?></a><?php endif; ?>:
        </p>

        <ul>
            <?php foreach ($highlights as $i => $highlight): ?>
            <li>
                <?php $counter = 0; foreach ($highlight->getCommits() as $rev => $uri): ?>
                <a href="<?php echo $uri ?>" target="_blank"><?php echo $rev ?></a><?php if ($counter): ?>, <?php endif; ?>
                <?php $counter++; endforeach; unset($counter); ?>
                <?php echo $originalHighlights[$i]->getContent() ?>
                <br>
                <textarea name="entry[highlights][<?php echo $name ?>][highlight][<?php echo $i ?>]"
                    rows="<?php echo max(2, intval(strlen($highlight->getContent()) / 50)) ?>"
                    cols="80"><?php echo $highlight->getContent() ?></textarea>
            </li>
            <?php endforeach; ?>
        </ul>

        <?php if ($highlights->hasSummaries()): ?>
        <?php $originalHSummaries = $originalHighlights->getSummaries() ?>
        <?php foreach ($highlights->getSummaries() as $i => $summary): ?>
        <p><?php echo $originalHSummaries[$i] ?></p>
        <p>
            <textarea name="entry[highlights][<?php echo $name ?>][summary][<?php echo $i ?>]"
                rows="5" cols="100"><?php echo $summary ?></textarea>
        </p>
        <?php endforeach; ?>
        <?php endif; ?>
        <?php endforeach; ?>

    </div>
    <?php endif; ?>

    <h3>翻訳者コメント</h3>

    <textarea name="entry[translator_comment]" rows="5" cols="80"><?php echo $entry->getTranslatorComment() ?></textarea>
    
    <p>
        <input type="submit" value="Create markdown" />
        <br />
        一応上のボタン押せばSQLiteに保存します。キャッシュを消さない限りは。。。削除したい場合は下のClearボタンを押してください。
    </p>
</form>

</article>

<hr />

<h2>Translation result</h2>
<div id="translated-text">
<?php $view['actions']->output('AWeekOfSymfonyBundle:Entry:show', array('entry' => $entry)) ?>
</div>

<form action="<?php echo $view['entry_router']->generate('awos_delete', $entry) ?>" method="post">
    <input type="hidden" name="_method" value="delete" />
    <p><input type="submit" value="Clear entry" id="clear-button" /></p>
</form>

<script>
jQuery(function($) {
    $('#translate-form').submit(function (event) {
        var form = $(this);

        $('#translated-text').load(form.attr('action'), form.serializeArray());

        event.preventDefault();
    });

    $('#clear-button').click(function(event) {
        if (!confirm('Are you sure!? 消すよ!?')) {
            event.preventDefault();
        }
    });
});
</script>
