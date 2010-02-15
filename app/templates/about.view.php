<?= $view->display('header_subpage')->appUrl($appUrl)->title($appName->with_subtitle('About')) ?>

<div id="content">
<div id="header">
<h1><a href="../"><?= $appName ?></a></h1>
</div>

<p><a href="../">Back Home</a></p>

<h2>About</h2>

<p>Just playing around really. If you're confused by this or interested in the details read "<a href="http://dekstop.de/weblog/2009/07/music_feeds/">Music Feeds -- Pop Culture Snippets, Opinionated Commentary, and Lots and Lots of Noise</a>" on my blog.</p>

<p>This is running on a single shared host. While it's somewhat an exercise in building a scalable service it shouldn't turn into an exercise in spending loads of money on infrastructure. So at the moment we mostly handpick the feeds we aggregate, with the immediate goal of aggregating thousands of them rather than millions.</p>

<p>By <a href="http://dekstop.de/">Martin Dittus</a> 2009.</p>

<p class="fineprint">If you're an author and don't like that we republish your content please <a href="../contact/">let us know</a>. Be assured that this was not created for financial gain. At the moment we don't even get indexed by Google.</p>

<h2>Disclaimer</h2>

<p>We make no uptime guarantees, and may decide to stop operating the service at any time.</p>
</div>

<?= $view->display('footer') ?>
