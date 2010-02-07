<? include('atom_header.view.php') ?>

<? foreach ($entries as $entry) { ?>
<entry>
  <id><?= $entry->unique_id->default('id_' . $appUrl . '_entry_' . $entry->id) ?></id>
  <title type="html"><?= $entry->title->sanitise()->raw() ?></title>
  <link rel="alternate" href="<?= $entry->link ?>" />
  <link rel="via" title="<?= $entry->feed_title ?>" href="<?= $entry->feed_link ?>" />
  <updated><?= $entry->date->date('Y-m-d\\TH:i:s\\Z') ?></updated>

  <? if ($entry->authors->is_array() && $entry->authors->count() > 0) { ?>
  <author><name><?= $entry->authors->implode('</name> <name>') ?></name></author>
  <? } else {?>
  <author><name>Unknown</name></author>
  <? } ?>

  <? if ($entry->categories->is_array() && $entry->categories->count() > 0) { ?>
  <category term="<?= $entry->categories->implode('" /><category term="') ?>" />
  <? } ?>

  <content type="html"><?= $entry->content->sanitise()->raw() ?></content>

  <? if ($entry->enclosures->is_array() && $entry->enclosures->count() > 0) { ?>
    <? foreach ($entry->enclosures as $e) { ?>
  <link rel="enclosure" href="<?= $e->url ?>" type="<?= $e->type ?>" length="<?= $e->length ?>" />
    <? } ?>
  <? } ?>
</entry>
<? } ?>

<? include('atom_footer.view.php') ?>
