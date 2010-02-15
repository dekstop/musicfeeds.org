<?= $view->display('header_subpage')->appUrl($appUrl)->title($appName->with_subtitle('Admin: Feed Info for ' . $feed->title)) ?>

<div id="content">
<div id="header">
<h1><a href="../"><?= $appName ?></a></h1>
</div>

<?= $view->display('admin_nav')->appUrl($appUrl) ?>

<h2><?= $feed->title ?></h2>

<p><?= $feed->description ?></p>

<ul>
  <li>URL: <a href="<?= $feed->url ?>"><?= $feed->url ?></a></li>
  <li>Added: <?= $feed->date_added->date('Y-m-d H:i') ?></li>
  <li>Last fetched: <?= $feed->date_last_fetched->date('Y-m-d H:i')->default('-') ?></li>
  <li>Last modified: <?= $feed->date_modified->date('Y-m-d H:i') ?></li>
  <li><?= $feed->active->if_equals('t', 'Active', 'Inactive') ?>, <?= $feed->fail_count ?> failed requests. 
    <? if ($feed->active->equals('t')) {?>
      <a href="<?= $appUrl ?>a/feed/deactivate?feed_id=<?= $feed->id ?>&amp;returnUrl=<?= $appUrl ?>a/feed?feed_id=<?= $feed->id ?>">deactivate</a>
    <? } else { ?>
      <a href="<?= $appUrl ?>a/feed/activate?feed_id=<?= $feed->id ?>&amp;returnUrl=<?= $appUrl ?>a/feed?feed_id=<?= $feed->id ?>">activate</a>
    <? } ?></li>
  <li>HTTP Last Modified: <?= $feed->http_last_modified->default('-') ?></li>
  <li>HTTP ETag: <?= $feed->http_etag->default('-') ?></li>
  <li>Feed ID: <?= $feed->unique_id->default('-') ?></li>
  <li>Feed TTL: <?= $feed->ttl->default('-') ?></li>
</ul>

</div>

<?= $view->display('footer') ?>
