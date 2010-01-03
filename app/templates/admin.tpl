{config_load file="smarty.conf" section="main"}
{include file="header_subpage.tpl" title="Admin"}

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
</style>

<div id="content">
<div id="header">
<h1><a href="../">{$app.name}</a></h1>
</div>

<p><a href="../">Back Home</a></p>

<h2>Add a Feed</h2>

<form action="." method="post">
<p>Feed URL: <input type="text" name ="feed_url" value="{$feedUrl|e}" /> <input type="submit" value="Send" /></p> 
<p>Username: <input type="text" name ="user" value="{$user|e}" /></p>
</form>

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
{foreach from=$batchimports item=item}
<tr{if $item.active=='t'}{if $item.imported=='t'} class="imported"{else} class="active"{/if}{else} class="inactive"{/if}>
	<td class="url"><a href="{$item.url|e}">{$item.url|excerpt:$urlExcerptLen}</a></td>
	<td class="date">{$item.date_added|e}</td>
	<td class="date">{$item.date_last_fetched|e}</td>
	<td class="date">{$item.date_modified|e}</td>
	<td>{$item.fail_count|e}</td>
	<td>{$item.imported|e}</td>
</tr>
{/foreach}
</table>

<h2>Failed Updates</h2>
<table class="log">
<tr>
	<th>URL</th>
	<th>Date Added</th>
	<th>Last Fetch</th>
	<th>Last Update</th>
	<th>Failures</th>
	<th>http_last_modified</th>
	<th>etag</th>
	<th>uid</th>
	<th>ttl</th>
</tr>
{foreach from=$failedFeeds item=item}
<tr{if $item.active=='t'} class="active"{else} class="inactive"{/if}>
	<td class="url"><a href="{$item.url|e}">{$item.title|excerpt:$titleExcerptLen}</a></td>
	<td class="date">{$item.date_added|e}</td>
	<td class="date">{$item.date_last_fetched|e}</td>
	<td class="date">{$item.date_modified|e}</td>
	<td>{$item.fail_count|e}</td>
	<td class="date">{$item.http_last_modified|e}</td>
	<td>{if $item.http_etag}y{else}n{/if}</td>
	<td>{$item.unique_id|e}</td>
	<td>{$item.ttl|e}</td>
</tr>
{/foreach}
</table>

<h2>Recent Updates</h2>
<table class="log">
<tr>
	<th>URL</th>
	<th>Date Added</th>
	<th>Last Fetch</th>
	<th>Last Update</th>
	<th>Failures</th>
	<th>http_last_modified</th>
	<th>etag</th>
	<th>uid</th>
	<th>ttl</th>
</tr>
{foreach from=$feeds item=item}
<tr{if $item.active=='t'} class="active"{else} class="inactive"{/if}>
	<td class="url"><a href="{$item.url|e}">{$item.title|excerpt:$titleExcerptLen}</a></td>
	<td class="date">{$item.date_added|e}</td>
	<td class="date">{$item.date_last_fetched|e}</td>
	<td class="date">{$item.date_modified|e}</td>
	<td>{$item.fail_count|e}</td>
	<td class="date">{$item.http_last_modified|e}</td>
	<td>{if $item.http_etag}y{else}n{/if}</td>
	<td>{$item.unique_id|e}</td>
	<td>{$item.ttl|e}</td>
</tr>
{/foreach}
</table>

</div>

{include file="footer.tpl"}
