<?= '<?xml version="1.0" encoding="utf-8"?>' ?>
<feed xmlns="http://www.w3.org/2005/Atom">
  <title><?= $title ?></title>
  <link href="<?= $linkUrl ?>"/>
  <link rel="self" href="<?= $selfUrl ?>"/>
  <updated><?= $date->date('Y-m-d\\TH:i:s\\Z') ?></updated>
  <id><?= $selfUrl ?></id>
  <generator>MicroLink 5.6</generator>
