
<textarea rows="10" cols="100">
<?php echo $markdown ?>
</textarea>

<div class="markdown">
<?php echo $view['markdown']->transform($markdown) ?>
</div>
