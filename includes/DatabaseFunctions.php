<?php 

function allJokes($pdo) {
	$stmt = $pdo->prepare('SELECT `joke`.`id`, `joketext`, `jokedate`, `name`, `email`
		FROM `joke` INNER JOIN `author`
			ON `authorid` = `author`.`id`');

	$stmt->execute();

	return $stmt->fetchAll();
}

function totalJokes($pdo)
{
	$stmt = $pdo->prepare('SELECT COUNT(*) FROM `joke`');
	$stmt->execute();

	$row = $stmt->fetch();

	return $row[0];
}

function getJoke($pdo, $id) {
	$stmt = $pdo->prepare('SELECT * FROM `joke` WHERE `id` = :id');

	$values = ['id' => $id];

	$stmt->execute($values);

	return $stmt->fetch();
}

function insertJoke($pdo, $values)	{
	$query = 'INSERT INTO `joke` SET ' . getPlaceholder($values);

	$values = processDates($values);

	$pdo->prepare($query)->execute($values);
}

function updateJoke($pdo, $values) {
	$query = sprintf('UPDATE `joke` SET %s WHERE `id` = :primaryKey', getPlaceholder($values));

	$values = processDates($values);

	// Set the :primaryKey variable
	$values['primaryKey'] = $values['id'];

	$pdo->prepare($query)->execute($values);
}

function deleteJoke($pdo, $id) {
	$stmt = $pdo->prepare('DELETE FROM `joke` WHERE `id` = :id');

	$stmt->execute([':id' => $id]);
}

function getPlaceholder($values)
{
	$placeholder = '';
	foreach (array_keys($values) as $key) {
		$placeholder .= "`{$key}` = :{$key},";
	}
	return rtrim($placeholder, ',');
}

function processDates($values)
{
	foreach ($values as $key => $value) {
		if ($value instanceof DateTime) {
			$values[$key] = $value->format('Y-m-d H:i:s');
		}
	}

	return $values;
}
