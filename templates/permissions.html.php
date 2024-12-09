<h2>Edit <?= $author->name ?>s Permissions</h2>

<form action="" method="post">
	<?php foreach ($permissions as $name => $value): ?>
	<div>
		<input 
			name="permissions[]" 
			type="checkbox" 
			value="<?= $value ?>" 
			<?= $author->hasPermission($value) ? 'checked' : ''  ?> 
		>
		<label><?= $name ?></label>
	</div>
	<?php endforeach ?>

	<input type="submit" value="Submit">
</form>