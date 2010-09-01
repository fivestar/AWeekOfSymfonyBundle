<!DOCTYPE html>
<html lang="ja-JP">
<head>
    <meta charset="utf-8" />

    <title>fivestar.fm</title>

    <!--[if lte IE 8]>
    <script src="/js/html5.js" type="text/javascript"></script>
    <![endif]-->

    <link rel="stylesheet" type="text/css" media="screen" href="/css/main.css" />
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="/js/main.js"></script>
</head>
<body>
<div id="container">

    <header>
        <div id="title">
            <h1><a href="<?php echo $view['router']->generate('homepage') ?>">fivestar.fm</a></h1>
        </div>
    </header>

    <div id="contents">
        <div id="main">
            <?php $view['slots']->output('_content') ?>
        </div>
    </div>

	<div>
		<article>
			<h2>Get source code<h2>

			<p><a href="http://github.com/fivestar/AWeekOfSymfonyBundle">fivestar/AWeekOfSymfonyBundle</a></p>
		</article>
	</div>

    <footer>
        <div id="footer_contents">
            <p class="copyright">&copy; 2010 Katsuhiro Ogawa</p>

            <p class="providers">
                <span class="provider">Powered by <a href="http://symfony-reloaded.org/" target="_blank">Symfony2</a></span>
                /
                <span class="provider">Hosted by <a href="http://www.unit-hosting.com/" target="_blank">UnitHosting</a></span>
            </p>
        </div>
    </footer>

</div>

</body>
</html>
