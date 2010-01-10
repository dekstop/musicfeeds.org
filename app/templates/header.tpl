<!DOCTYPE HTML>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title>{#appName#}{if isset($title)}: {$title|e}{/if}</title>
  
  <link rel="stylesheet" href="./styles.css" type="text/css" />
  <link rel="alternate" type="application/atom+xml" title="Atom" href="{searchURL base='./feed/' q=$q f=$f c=$c n=$n lfmUser=$lfmUser|e}" />
  
  <script type="text/javascript" src="./js/jquery.js"></script>
{literal}
  <script type="text/javascript">
  $(document).ready(function(){
    $('.content img').each(function(){
      $(this).bind('error', function(){$(this).css('display', 'none');});
    });
  });
  </script>
{/literal}

{if $lowerOpacity==TRUE}
{literal}
  <style type="text/css">
  .item {
    filter:alpha(opacity=30);
    -moz-opacity: 0.3;
    -khtml-opacity: 0.3;
    opacity: 0.3;
  }
  </style>
{/literal}
{/if}

</head>
<body>
<div id="main">
