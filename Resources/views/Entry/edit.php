<?php $view->extend('AWeekOfSymfonyBundle::layout') ?>

<article>

<h2><?php echo $entry->getSubject() ?></h2>

<form action="<?php echo $view['entry_router']->generate('awos_translate', $entry) ?>" method="post" id="translate-form">

    <div class="origin"><?php echo $entry->getSummary() ?></div>
    <div>
        <textarea name="entry[summary]" rows="5" cols="80"><?php echo $entry->getSummary() ?></textarea>
    </div>

    <?php if ($entry->hasMailingList()): ?>
    <h3>開発メーリングリスト</h3>

    <div class="origin">
        <ul>
            <?php foreach ($entry->getMailinglist() as $i => $thread): ?>
            <li>
                <a href="<?php echo $thread->getUri() ?>" target="_blank"><?php echo $thread->getSubject() ?></a>
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
        <h4><?php echo $highlights->getLabel(); ?></h4>

        <ul>
        <?php foreach ($highlights as $i => $highlight): ?>
            <li>
                <?php $counter = 0; foreach ($highlight->getCommits() as $rev => $uri): ?>
                <a href="<?php echo $uri ?>" target="_blank"><?php echo $rev ?></a><?php if ($counter): ?>, <?php endif; ?>
                <?php $counter++; endforeach; unset($counter); ?>
                <?php echo $highlight->getContent() ?>
                <br>
                <textarea name="entry[highlights][<?php echo $name ?>][<?php echo $i ?>]" rows="2" cols="80"><?php echo $highlight->getContent() ?></textarea>
            </li>
        <?php endforeach; ?>
        </ul>
        <?php endforeach; ?>

        <?php if ($entry->hasOtherChanges()): ?>
        <p><a href="<?php echo $entry->getOtherChangesUri() ?>" target="_blank">その他多数</a></p>
        <?php endif; ?>
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

<p>Translated: </p>
<div id="translated-text">&nbsp;</div>

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
