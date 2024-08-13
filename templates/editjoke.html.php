<?php if (empty($joke) || $userId == $joke['authorid']): ?>
<form action="" method="post">
	<input type="hidden" name="joke[id]" value="<?= $joke['id'] ?? '' ?>">
	<label for="joketext">Type your joke here:</label>
	<textarea name="joke[joketext]" 
		id="joketext" 
		cols="40" 
		rows="3"
	><?= $joke['joketext'] ?? '' ?></textarea>
	<input type="submit" value="Save">
</form>
<?php else: ?>
<p>You may only edit jokes taht you posted.</p>
<?php endif ?>
