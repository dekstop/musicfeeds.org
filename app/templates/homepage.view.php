<? include('header.view.php') ?>

<div id="nav">
<p class="description">This is a simple blog filter, modulated by Last.fm attention data. A personalised <em>river of news</em> of pop culture snippets, opinionated commentary, and lots and lots of noise. As a website and as a feed.</p>
<form action="." method="get" class="search">
<? if ($lastfmFailed->is_true()) { ?>
<p class="error">Sorry, could not load Last.fm data for user <a href="http://last.fm/user/<?= $lfmUser ?>"><?= $lfmUser ?></a>. Does the account exist?</p>
<? } ?>
<p>Your Last.fm user name:
<input type="text" class="i_lfm" name="lfm:user" value="<?= $lfmUser ?>" /></p>
<p>Optional filter:
<input type="text" class="i_filter" name="q" value="<?= $q ?>" />
<input type="hidden" name="f" value="<?= $f ?>" />
<input type="hidden" name="c" value="<?= $c ?>" />
<input type="hidden" name="n" value="<?= $n ?>" /></p>
<p><input type="submit"/></p>
</form>
<p><a href="./about/">About</a>, <a href="./contact/">Contact</a>.</p>

<? if ($artists->count() > 0) { ?>
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

<a href="<?= $entry->feed_link ?>"><?= $entry->feed_title->strip_tags() ?></a><span class="star"><a href="<?= searchUrl(array('feed_id'=>$entry->feed_id, 'm'=>$n)) ?>">*</a></span><br />
<span class="date"><?= $entry->date->date('Y-m-d') ?></span> 

<? if ($entry->categories->is_array() && $entry->categories->count()>0 ) { ?>
<span class="category">
<? foreach ($entry->categories as $category) { ?>
&middot; <a href="<?= searchUrl(array('category'=>$category, 'f'=>$f, 'c'=>$c, 'n'=>$n, 'lfmUser'=>$lfmUser)) ?>"><?= $category ?></a>
<? } ?>
</span>
<? } ?></p>

<div class="content"><?= $entry->content->excerpt($c)->raw() // FIXME: this is madly insecure as soon as excerpt() stops sanitising. can't chain excerpt+sanitise to address this: it double-encodes. need a better/simpler way. ?></div>
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

<? include('footer.view.php') ?>
