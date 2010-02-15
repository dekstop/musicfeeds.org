<!DOCTYPE HTML>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title><?= $title ?></title>
  
  <link rel="stylesheet" href="./styles.css" type="text/css" />
  <link rel="alternate" type="application/atom+xml" title="Atom" href="<?= $feedUrl ?>" />
  
  <script type="text/javascript" src="./js/jquery.js"></script>
  <script type="text/javascript">
  $(document).ready(function(){
    $('.content img').each(function(){
      $(this).bind('error', function(){$(this).css('display', 'none');});
    });
  });
  </script>

<? if ($showHomepage->is_true()) { ?>
  <style type="text/css">
  .item {
    filter:alpha(opacity=30);
    -moz-opacity: 0.3;
    -khtml-opacity: 0.3;
    opacity: 0.3;
  }
  </style>
<? } ?>

</head>
<body>
<div id="main">
