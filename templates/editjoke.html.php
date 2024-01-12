<form action="" method="post">
	<input type="hidden" name="jokeid" value="<?=$joke['id']?>">
	<label for="joketext">Type your joke here:</label>
	<textarea name="joketext" 
		id="joketext" 
		cols="40" 
		rows="3"
	><?= htmlspecialchars($joke['joketext'], ENT_QUOTES, 'UTF-8') ?></textarea>
	<input type="submit" value="Save">
</form>