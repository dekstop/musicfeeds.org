<?= $view->display('header_subpage')->appUrl($appUrl)->title($appName->with_subtitle('404')) ?>

<div id="content">
<div id="header">
<h1><a href="<?= $appUrl ?>"><?= $appName ?></a></h1>
</div>

<p><a href="<?= $appUrl ?>">Back Home</a></p>

<h2>404 Page not found</h2>

<p><?= $msg ?></p>

<p>...</p>

</div>

<?= $view->display('footer') ?>
