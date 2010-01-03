{config_load file="smarty.conf" section="main"}
{include file="header_subpage.tpl" title="Sources"}

<div id="content">
<div id="header">
<h1><a href="../">{$app.name}</a></h1>
</div>

<p><a href="../">Back Home</a></p>
<h2>Sources</a>
{if (count($feeds)==0)}
<p class="emptyresult">No feeds found :(</p>
{else}
{foreach from=$feeds item=feed}
<h3><a href="{$feed.link|e}">{$feed.title|sanitise}</a></h3>
<p class="description"> 
<quote>{$feed.description|sanitise}</quote><br/>
<!--
URL: {$feed.url|e}<br/>
Actual URL: {$feed.actual_url|e}<br/>
Last fetched: {$feed.date_last_fetched|e}<br/>
Fail count: {$feed.fail_count|e}<br/>
Active: {$feed.active|e}<br/>
-->
</p>
{/foreach}
{/if}

</div>

{include file="footer.tpl"}
