{config_load file="smarty.conf" section="main"}
{include file="header.tpl"}

<div id="nav">
<p class="description">This is a simple blog filter, modulated by Last.fm attention data. A personalised <em>river of news</em> of pop culture snippets, opinionated commentary, and lots and lots of noise. As a website and as a feed.</p>
<form action="." method="get" class="search">
{if $lastfmFailed}
<p class="error">Sorry, could not load Last.fm data.</p>
{/if}
<p>Your Last.fm user name:
<input type="text" class="i_lfm" name="lfm:user" value="{$lfmUser|e}" /></p>
<p>Optional filter:
<input type="text" class="i_filter" name="q" value="{$q|e}" />
<input type="hidden" name="f" value="{$f|e}" />
<input type="hidden" name="c" value="{$c|e}" />
<input type="hidden" name="n" value="{$n|e}" /></p>
<p><input type="submit"/></p>
</form>
<p><a href="./about/">About</a>, <a href="./contact/">Contact</a>.</p>

{if count($artists) > 0}
<p class="description">We're filtering with these artists from your Last.fm profile: 
{implode from=$artists separator=', '}.</p>
<p class="description">Hit refresh for a different selection.</p>
{/if}
</div>

<div id="content">

<div id="header">
<h1><a href="./">{$app.name}</a></h1>
</div>
{if count($entries) == 0}
<p class="emptyresult">No results found for this query :(</p>
<p>Know a blog that we should index? <a href="./contact/">tell us!</a></p>
{else}

{foreach from=$entries item=entry}
<div class="item">
<h1><a href="{$entry.link|e}">{$entry.title|sanitise|e}</a></h1>
<p class="source"> 
By 
{if is_array($entry.authors) && count($entry.authors)>0}
<span class="author">{implode from=$entry.authors separator=', '}</span>,
{/if}

<a href="{$entry.feed_link|e}">{$entry.feed_title|sanitise|e}</a><span class="star"><a href="{searchURL feed_id=$entry.feed_id m=$n|e}">*</a></span><br />
<span class="date">{$entry.date|date_format:'Y-m-d'|e}</span> 

{if is_array($entry.categories) && count($entry.categories)>0}
<span class="category">
{foreach from=$entry.categories item=category}
&middot; <a href="{searchURL category=$category f=$f c=$c n=$n lfmUser=$lfmUser|e}">{$category|e}</a>
{/foreach}
</span>
{/if}</p>

<div class="content">{$entry.content|excerpt:$c}</div>
<p class="footer more">Read more at <a href="{$entry.link|e}">{$entry.feed_title|sanitise|e} - {$entry.title|sanitise|e}</a>.</p>

{if is_array($entry.enclosures) && count($entry.enclosures) > 0}
<ul class="enclosures">
{foreach from=$entry.enclosures item=enclosure}
	<li><a href="{$enclosure.url|e}">{filename url=$enclosure.url|e}</a> ({$enclosure.length|byte_format|e})</li>
{/foreach}
</ul>
{/if}
</div>

{/foreach}
{/if}

</div>

{include file="footer.tpl"}
