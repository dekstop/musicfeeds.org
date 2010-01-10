{config_load file="smarty.conf" section="main"}
{include file="atom_header.tpl" title="{#appName#}: {$lfmUser} {$q}" link="{searchURL base={#appUrl#} q=$q f=$f c=$c n=$n lfmUser=$lfmUser}"}

{foreach from=$entries item=entry}
<entry>
  <id>{if $entry.unique_id}{$entry.unique_id}{else}id_{#appUrl#|e}_entry_{$entry.id|e}{/if}</id>
  <title type="html"><![CDATA[ {$entry.title|sanitise} ]]></title>
  <link rel="alternate" href="{$entry.link|e}" />
  <link rel="via" title="{$entry.feed_title|e}" href="{$entry.feed_link|e}" />
  <updated>{strtotime($entry.date)|date_format:'Y-m-d\\TH:i:s\\Z'|e}</updated>

  {if is_array($entry.authors) && count($entry.authors)>0}
  <author><name>{implode from=$entry.authors separator='</name> <name>'}</name></author>
  {else}
  <author><name>Unknown</name></author>
  {/if}

  {if is_array($entry.categories) && count($entry.categories)>0}
  <category term="{implode from=$entry.categories separator='" /><category term="'}" />
  {/if}

  <content type="html"><![CDATA[ {$entry.content|sanitise} ]]></content>

  {if is_array($entry.enclosures) && count($entry.enclosures)>0}
  {foreach from=$entry.enclosures item=e}
  <link rel="enclosure" href="{$e.url|e}" type="{$e.type|e}" length="{$e.length|e}" />
  {/foreach}
  {/if}
</entry>
{/foreach}

{include file="atom_footer.tpl"}
