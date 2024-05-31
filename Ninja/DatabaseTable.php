<?php

namespace Ninja;

class DatabaseTable {
	public function __construct(private \PDO $pdo, private string $table, private string $primaryKey)
	{
	}

	public function findAll() {
		$stmt = $this->pdo->prepare("SELECT * FROM `{$this->table}`");
		$stmt->execute();

		return $stmt->fetchAll();
	}

	public function total()
	{
		$stmt = $this->pdo->prepare("SELECT COUNT(*) FROM `{$this->table}`");
		$stmt->execute();

		return $stmt->fetch()[0];
	}

	public function find($field, $value) {
		$stmt = $this->pdo->prepare("SELECT * FROM `{$this->table}` WHERE `{$field}` = :value");

		$stmt->execute(['value' => $value]);

		return $stmt->fetchAll();
	}

	private function insert($values)	{
		$query = "INSERT INTO `{$this->table}` SET " . $this->getPlaceholder($values);

		$values = $this->processDates($values);

		$this->pdo->prepare($query)->execute($values);
	}

	private function update($values) {
		$query = sprintf(
			"UPDATE `{$this->table}` SET %s WHERE `{$this->primaryKey}` = :primaryKey", 
			$this->getPlaceholder($values)
		);

		$values = $this->processDates($values);

		// Set the :primaryKey variable
		$values['primaryKey'] = $values['id'];

		$this->pdo->prepare($query)->execute($values);
	}

	public function save($record) {
		try {
			if (empty($record[$this->primaryKey])) {
				unset($record[$this->primaryKey]);
			}
			$this->insert($record);
		} catch (\PDOException $e) {
			$this->update($record);
		}
	}

	public function delete($field, $value) {
		$stmt = $this->pdo->prepare("DELETE FROM `{$this->table}` WHERE `{$field}` = :value");

		$stmt->execute([':value' => $value]);
	}

	private function getPlaceholder($values)
	{
		$placeholder = '';
		foreach (array_keys($values) as $key) {
			$placeholder .= "`{$key}` = :{$key},";
		}
		return rtrim($placeholder, ',');
	}

	private function processDates($values)
	{
		foreach ($values as $key => $value) {
			if ($value instanceof \DateTime) {
				$values[$key] = $value->format('Y-m-d H:i:s');
			}
		}

		return $values;
	}
}