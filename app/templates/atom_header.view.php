<?= '<?xml version="1.0" encoding="utf-8"?>' ?>
<feed xmlns="http://www.w3.org/2005/Atom">
  <title><?= $appName->with_subtitle($lfmUser . " " . $q) ?></title>
  <link href="<?= searchUrl(array('base'=>$appUrl, 'q'=>$q, 'f'=>$f, 'c'=>$c, 'n'=>$n, 'lfmUser'=>$lfmUser)) ?>"/>
  <link rel="self" href="<?= searchUrl(array('base'=>"${appUrl}/feed", 'q'=>$q, 'f'=>$f, 'c'=>$c, 'n'=>$n, 'lfmUser'=>$lfmUser)) ?>"/>
  <updated><?= $now->date('Y-m-d\\TH:i:s\\Z') ?></updated>
  <id><?= searchUrl(array('base'=>$appUrl, 'q'=>$q, 'f'=>$f, 'c'=>$c, 'n'=>$n, 'lfmUser'=>$lfmUser)) ?></id>
  <generator>MicroLink 5.6</generator>
