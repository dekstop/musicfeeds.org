<?= $view->display('header_subpage')->appUrl($appUrl)->title($appName->with_subtitle('Contact')) ?>

<div id="content">
<div id="header">
<h1><a href="../"><?= $appName ?></a></h1>
</div>

<p><a href="../">Back Home</a></p>

<h2>Contact</h2>

<p>Thank you for the comment!</p>

</div>

<?= $view->display('footer') ?>
