<?php $view->extend('AWeekOfSymfonyBundle::layout') ?>

<article id="translated-text">
&nbsp;
</article>

<article>

<h2><?php echo $entry->getTitle() ?></h2>

<form action="<?php echo $requestUri ?>" method="post" id="translate-form">

    <div class="origin"><?php echo $entry->getSummary() ?></div>
    <div>
        <textarea name="entry[summary]" rows="5" cols="80"><?php echo $entry->getSummary() ?></textarea>
    </div>

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

    <h3>開発ハイライト</h3>

    <div class="origin">
        <?php foreach ($entry->getAllHighlights() as $name => $highlights): ?>
        <h4><?php echo $name; ?></h4>

        <ul>
        <?php foreach ($highlights as $highlight): ?>
            <li>
                <?php $counter = 0; foreach ($highlight->getCommits() as $rev => $uri): ?>
                <a href="<?php echo $uri ?>" target="_blank"><?php echo $rev ?></a><?php if ($counter): ?>, <?php endif; ?>
                <?php $counter++; endforeach; unset($counter); ?>
                <?php echo $highlight->getContent() ?>
                <br>
                <textarea name="" rows="2" cols="80"><?php echo $highlight->getContent() ?></textarea>
            </li>
        <?php endforeach; ?>
        </ul>
        <?php endforeach; ?>

        <p><a href="<?php echo $entry->getOtherChangesUri() ?>" target="_blank">その他多数</a></p>
    </div>

    <h3>翻訳者コメント</h3>

    <textarea name="entry[translator_comment]" rows="5" cols="80"></textarea>
    
    <p>
        <input type="submit" value="Create markdown" />
    </p>
</form>

</article>

<script>
jQuery(function($) {
    $('#translate-form').submit(function (event) {
        var form = $(this);

        $('#translated-text').load(form.attr('action'), form.serializeArray());

        event.preventDefault();
    });
});
</script>
