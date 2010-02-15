<?= $view->display('header', 
  array(
    'title' => $appName->with_subtitle($title),
    'feedUrl' => buildSearchUrl($search, $appUrl . 'feed/'),
    'showHomepage' => $showHomepage
  )) ?>

<div id="nav">
<p class="description">This is a simple blog filter, modulated by Last.fm attention data. A personalised <em>river of news</em> of pop culture snippets, opinionated commentary, and lots and lots of noise. As a website and as a feed.</p>
<form action="." method="get" class="search">
<? if ($artists->is_null()) { ?>
<p class="error">Sorry, could not load Last.fm data for user <a href="http://last.fm/user/<?= $search->lastfmUser ?>"><?= $search->lastfmUser ?></a>. Does the account exist?</p>
<? } else if (!$search->lastfmUser->is_empty() && $artists->count()==0) { ?>
<p class="error">Sorry, but Last.fm user <a href="http://last.fm/user/<?= $search->lastfmUser ?>"><?= $search->lastfmUser ?></a> does not seem to have any scrobbles.</p>
<? } ?>
<p>Your Last.fm user name:
<input type="text" class="i_lfm" name="lfm:user" value="<?= $search->lastfmUser ?>" /></p>
<p>Optional filter:
<input type="text" class="i_filter" name="q" value="<?= $search->q ?>" />
<input type="hidden" name="f" value="<?= $search->f ?>" />
<input type="hidden" name="c" value="<?= $search->c ?>" />
<input type="hidden" name="n" value="<?= $search->n ?>" /></p>
<p><input type="submit"/></p>
</form>
<p><a href="./about/">About</a>, <a href="./contact/">Contact</a>.</p>

<? if (!$artists->is_null() && $artists->count() > 0) { ?>
<p class="description">We're filtering with these artists from your Last.fm profile: 
<?= $artists->implode(', ') ?>.</p>
<p class="description">Hit refresh for a different selection.</p>
<? } ?>
</div>

<div id="content">

<div id="header">
<h1><a href="./"><?= $appName ?></a></h1>
</div>
<? if ($entries->count() == 0) { ?>
<p class="emptyresult">No results found for this query :(</p>
<p>Know a blog that we should index? <a href="./contact/">tell us!</a></p>
<? } else { ?>

<? foreach ($entries as $entry) { ?>
<div class="item">
<h1><a href="<?= $entry->link ?>"><?= $entry->title->sanitise()->raw() ?></a></h1>
<p class="source"> 
By 
<? if ($entry->authors->is_array() && $entry->authors->count()>0 ) { ?>
<span class="author"><?= $entry->authors->implode(', ') ?></span>,
<? } ?>

<a href="<?= $entry->feed_link ?>"><?= $entry->feed_title->strip_tags() ?></a><span class="star"><a href="<?= buildFeedSearchUrl($entry->feed_id) ?>">*</a></span><br />
<span class="date"><?= $entry->date->date('Y-m-d') ?></span> 

<? if ($entry->categories->is_array() && $entry->categories->count()>0 ) { ?>
<span class="category">
<? foreach ($entry->categories as $category) { ?>
&middot; <a href="<?= buildCategorySearchUrl($category) ?>"><?= $category ?></a>
<? } ?>
</span>
<? } ?></p>

<div class="content"><?= $entry->content->excerpt($search->c)->raw() // FIXME: this is madly insecure as soon as excerpt() stops sanitising. can't chain excerpt+sanitise to address this: it double-encodes. need a better/simpler way. ?></div>
<p class="footer more">Read more at <a href="<?= $entry->link ?>"><?= $entry->feed_title->strip_tags() ?> - <?= $entry->title->strip_tags() ?></a>.</p>

<? if ($entry->enclosures->is_array() && $entry->enclosures->count() > 0) { ?>
<ul class="enclosures">
<? foreach ($entry->enclosures as $enclosure) { ?>
  <li><a href="<?= $enclosure->url ?>"><?= $enclosure->url->filename() ?></a> (<?= $enclosure->length->bytes() ?>)</li>
<? } /* foreach */ ?>
</ul>
<? } // if ?>
</div>

<? } // foreach ?>
<? } // if ?>

</div>

<?= $view->display('footer') ?>
