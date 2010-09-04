<?php $view->extend('AWeekOfSymfonyBundle::layout') ?>

<article>

<h2><?php echo $entry->getTitle() ?></h2>

<form action="#" method="post">
    <div class="origin"><?php echo $entry->getSummary() ?></div>
    <div><?php echo $form['summary']->render(array('rows' => 5, 'cols' => 80)) ?></div>

    <h3>開発メーリングリスト</h3>

    <div class="origin">
        <ul>
            <?php foreach ($entry->getMailinglist() as $uri => $title): ?>
            <li>
                <a href="<?php echo $uri ?>" target="_blank"><?php echo $title ?></a>
                <br>
                <input type="text" name="" value="<?php echo $title ?>" size="120">
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

    <?php echo $form['translator']->render(array('rows' => 5, 'cols' => 80)) ?>
    
</form>

</article>

