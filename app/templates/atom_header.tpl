<?xml version="1.0" encoding="utf-8"?>
<feed xmlns="http://www.w3.org/2005/Atom">
  <title>{$title|e}</title>
  <link href="{$link|e}"/>
{if $self}
  <link rel="self" href="{$self|e}"/>
{/if}
  <updated>{date('Y-m-d\\TH:i:s\\Z')|e}</updated>
  <id>{$link|e}</id>
  <generator>MicroLink 5.6</generator>
