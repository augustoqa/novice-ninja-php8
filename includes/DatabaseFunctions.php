<?php 

function findAll($pdo, $table) {
	$stmt = $pdo->prepare("SELECT * FROM `{$table}`");
	$stmt->execute();

	return $stmt->fetchAll();
}

function total($pdo, $table)
{
	$stmt = $pdo->prepare("SELECT COUNT(*) FROM `${table}`");
	$stmt->execute();

	return $stmt->fetch()[0];
}

function find($pdo, $table, $field, $value) {
	$stmt = $pdo->prepare("SELECT * FROM `${table}` WHERE `{$field}` = :value");

	$stmt->execute(['value' => $value]);

	return $stmt->fetchAll();
}

function insert($pdo, $table, $values)	{
	$query = "INSERT INTO `{$table}` SET " . getPlaceholder($values);

	$values = processDates($values);

	$pdo->prepare($query)->execute($values);
}

function update($pdo, $table, $primaryKey, $values) {
	$query = sprintf("UPDATE `{$table}` SET %s WHERE `{$primaryKey}` = :primaryKey", getPlaceholder($values));

	$values = processDates($values);

	// Set the :primaryKey variable
	$values['primaryKey'] = $values['id'];

	$pdo->prepare($query)->execute($values);
}

function delete($pdo, $table, $field, $value) {
	$stmt = $pdo->prepare("DELETE FROM `{$table}` WHERE `{$field}` = :value");

	$stmt->execute([':value' => $value]);
}

function save($pdo, $table, $primaryKey, $record) {
	try {
		if (empty($record[$primaryKey])) {
			unset($record[$primaryKey]);
		}
		insert($pdo, $table, $record);
	} catch (PDOException $e) {
		update($pdo, $table, $primaryKey, $record);
	}
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
