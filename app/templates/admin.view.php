<?= $view->display('header_subpage')->appUrl($appUrl)->title($appName->with_subtitle('Admin')) ?>

<style>
table.log {
  font-size: 0.8em;
  border-collapse: collapse;
  border: 3px solid #cccccc;
  width: 900px;
}
table.log th {
  border: 1px dotted gray;
  background-color: #f2f2f2;
  padding: 4px;
}       
table.log td {
  border: 1px dotted gray;
  padding: 2px;
}       

table.log .active {
}
table.log .inactive {
  background: #ffeeee;
}
table.log .imported {
  background: #eeffee;
}
table.log .date {
  font-size: 0.8em;
}
table.log .url {
}
#spinner {
  font-size: 0.5em;
}
</style>

<script type="text/javascript">
function getFeedInfo(url) {
  $.getJSON("<?= $appUrl ?>/ajax/feedinfo?url=" + encodeURIComponent(url),
    function(data) {
      if (data.length==0) {
        $('#spinner').html("This URL is new! Yay");
      } else {
        $('#spinner').html("This feed is already known. Added " + data[0].date_added);
      }
    });
  $('#spinner').html("Checking ...");
}

$(document).ready(function(){
  if ($('#feed_url').val()!='') getFeedInfo($('#feed_url').val());
});
</script>

<div id="content">
<div id="header">
<h1><a href="../"><?= $appName ?></a></h1>
</div>

<?= $view->display('admin_nav')->appUrl($appUrl) ?>

<a name="add"></a>
<h2>Add a Feed</h2>

<form action="." method="post">
<p>Feed URL: <input type="text" name ="feed_url" id="feed_url" value="<?= $feedUrl ?>" onchange="getFeedInfo(this.value)"/> <input type="submit" value="Send" /> <span id="spinner"></span></p> 
<p>Username: <input type="text" name ="user" value="<?= $user ?>" /></p>
</form>

<a name="imports"></a>
<h2>Recent Imports</h2>
<table class="log">
<tr>
  <th>URL</th>
  <th>Date Added</th>
  <th>Last Fetch</th>
  <th>Last Update</th>
  <th>Failures</th>
  <th>Imported?</th>
</tr>

<? foreach ($batchimports as $item) { ?>
<tr class="<?= $item->active->if_equals('t', $item->imported->if_equals('t', 'imported', 'active'), 'inactive') ?>">
  <td class="url"><a href="<?= $item->url ?>"><?= $item->url->excerpt($urlExcerptLen) ?></a></td>
  <td class="date"><?= $item->date_added->date('Y-m-d H:i') ?></td>
  <td class="date"><?= $item->date_last_fetched->date('Y-m-d H:i')->default('-') ?></td>
  <td class="date"><?= $item->date_modified->date('Y-m-d H:i') ?></td>
  <td><?= $item->fail_count ?></td>
  <td><?= $item->imported ?></td>
</tr>
<? } ?>
</table>

<a name="failures"></a>
<h2>Failed Updates</h2>
<table class="log">
<tr>
  <th>Feed</th>
  <th>Date Added</th>
  <th>Last Fetch</th>
  <th>Last Update</th>
  <th>Failures</th>
  <th>http_last_modified</th>
  <th>etag</th>
  <th>uid</th>
  <th>ttl</th>
</tr>
<? foreach ($failedFeeds as $item) { ?>
<tr class="<?= $item->active->if_equals('t', 'active', 'inactive') ?>">
  <td class="url"><a href="<?= $appUrl ?>a/feed?feed_id=<?= $item->id ?>"><?= $item->title->excerpt($titleExcerptLen) ?></a></td>
  <td class="date"><?= $item->date_added->date('Y-m-d H:i') ?></td>
  <td class="date"><?= $item->date_last_fetched->date('Y-m-d H:i')->default('-') ?></td>
  <td class="date"><?= $item->date_modified->date('Y-m-d H:i') ?></td>
  <td><?= $item->fail_count ?></td>
  <td class="date"><?= $item->http_last_modified->default('-') ?></td>
  <td><?= $item->http_etag->is_empty() ? 'n' : 'y' ?></td>
  <td><?= $item->unique_id->default('-') ?></td>
  <td><?= $item->ttl->default('-') ?></td>
</tr>
<? } ?>
</table>

<a name="updates"></a>
<h2>Recent Updates</h2>
<table class="log">
<tr>
  <th>Feed</th>
  <th>Date Added</th>
  <th>Last Fetch</th>
  <th>Last Update</th>
  <th>Failures</th>
  <th>http_last_modified</th>
  <th>etag</th>
  <th>uid</th>
  <th>ttl</th>
</tr>
<? foreach ($feeds as $item) { ?>
  <tr class="<?= $item->active->if_equals('t', 'active', 'inactive') ?>">
    <td class="url"><a href="<?= $appUrl ?>a/feed?feed_id=<?= $item->id ?>"><?= $item->title->excerpt($titleExcerptLen) ?></a></td>
  <td class="date"><?= $item->date_added->date('Y-m-d H:i') ?></td>
  <td class="date"><?= $item->date_last_fetched->date('Y-m-d H:i')->default('-') ?></td>
  <td class="date"><?= $item->date_modified->date('Y-m-d H:i') ?></td>
  <td><?= $item->fail_count ?></td>
  <td class="date"><?= $item->http_last_modified->default('-') ?></td>
  <td><?= $item->http_etag->is_empty() ? 'n' : 'y' ?></td>
  <td><?= $item->unique_id->default('-') ?></td>
  <td><?= $item->ttl->default('-') ?></td>
</tr>
<? } ?>
</table>

<a name="comments"></a>
<h2>Recent Comments</h2>
<table class="log">
<tr>
  <th>Date</th>
  <th>Name</th>
  <th>Email</th>
  <th>Comment</th>
  <th>URL</th>
</tr>
<? foreach ($comments as $item) { ?>
<tr>
  <td class="date"><?= $item->date ?></td>
  <td><?= $item->author_name ?></td>
  <td><? if (!$item->author_email->is_empty()) { ?><a href="mailto:<?= $item->author_email?>?subject=[<?= $appName ?>] contact form <?= $item->date ?>"><?= $item->author_email ?><? } ?></td>
  <td><?= $item->comments ?></td>
  <td class="url"><a href="<?= $item->url ?>"><?= $item->url->excerpt($urlExcerptLen) ?></a></td>
</tr>
<? } ?>
</table>

</div>

<?= $view->display('footer') ?>
