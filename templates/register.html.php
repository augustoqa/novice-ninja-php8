<?php if (!empty($errors)): ?>
<div class="errors">
	<p>Your account could not be created, please check the following:</p>
	<ul>
		<?php foreach ($errors as $error): ?>
		<li><?= $error ?></li>
		<?php endforeach ?>
	</ul>
</div>
<?php endif ?>
<form action="" method="post" novalidate>
	<label for="email">Your email address</label>
	<input type="email" name="author[email]" value="<?= $author['email'] ?? '' ?>" id="email">

	<label for="name">Your name</label>
	<input type="text" name="author[name]" value="<?= $author['name'] ?? '' ?>" id="name">

	<label for="password">Password</label>
	<input type="password" name="author[password]" value="<?= $author['password'] ?? '' ?>" id="password">

	<input type="submit" name="submit" value="Register account">
</form>