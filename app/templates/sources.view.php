<?= $view->display('header_subpage')->appUrl($appUrl)->title($appName->with_subtitle('Sources')) ?>

<div id="content">
<div id="header">
<h1><a href="../"><?= $appName ?></a></h1>
</div>

<p><a href="../">Back Home</a></p>
<h2>Sources</a>
<? if ($feeds->count() == 0) { ?>
<p class="emptyresult">No feeds found :(</p>
<? } else { ?>
  <? foreach ($feeds as $feed) { ?>
<h3><a href="<?= $feed->link ?>"><?= $feed->title->sanitise() ?></a></h3>
<p class="description"> 
<? if (!$feed->description->is_empty_string()) { ?><quote><?= $feed->description->sanitise() ?></quote><br/><br/><? } ?>
URL: <?= $feed->url ?><br/>
Actual URL: <?= $feed->actual_url ?><br/>
Last fetched: <?= $feed->date_last_fetched ?><br/>
Fail count: <?= $feed->fail_count ?><br/>
Active: <?= $feed->active ?><br/>
</p>
  <? } ?>
<? } ?>

</div>

<?= $view->display('footer') ?>
