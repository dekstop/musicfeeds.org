<?= $view->display('header_subpage')->title($appName->with_subtitle('Contact')) ?>

<div id="content">
<div id="header">
<h1><a href="../"><?= $appName ?></a></h1>
</div>

<p><a href="../">Back Home</a></p>

<h2>Contact</h2>

<p>Know a blog that we should index? Want us to remove your content? Tell us about it!</p>

<form action="." method="post">
<p>Blog URL or feed we should index: <input type="text" name ="url" /> <input type="submit" value="Send" /></p> 
<p class="description">We will review all submissions before they get accepted.</p>
<p><br />And optionally...</p>
<p>Your name: <input type="text" name ="author_name" /></p>
<p>Your email: <input type="text" name ="author_email" /></p>
<p>Comments: <br /><textarea name="comments" cols="30" rows="5"></textarea></p>
</form>

</div>

<?= $view->display('footer') ?>
