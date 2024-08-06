<?php if (isset($errorMessage)): ?>
	<div class="errors">Sorry, your username and password could not be found.</div>
<?php endif ?>

<form method="post" action="">
	<label for="email">Your email address</label>
	<input type="email" id="email" name="email">

	<label for="password">Your password</label>
	<input type="password" name="password" id="password">

	<input type="submit" name="login" value="Log in">
</form>

<p>Don't have an account? <a href="/author/registrationform">Click here to register</a></p>