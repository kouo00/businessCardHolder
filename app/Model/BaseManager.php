<?php declare(strict_types=1);


namespace App\Model;

use Nette\Database\Explorer;
use Nette\Database\Table\ActiveRow;

abstract class BaseManager
{
	protected Explorer $database;

	public function __construct(Explorer $database)
	{
		$this->database = $database;
	}

	public function getTable(string $table): array
	{
		return $this->database->table($table)->where(BusinessCardManager::COLUMN_USERS_ID, 1)->fetchAll();
	}

	public abstract function getSelection();


	public function getRow(array $values): ActiveRow
	{
		$selection = $this->getSelection();
		foreach ($values as $column => $value) {
			$selection->where($column, $value);
		}
		return $selection->fetch();
	}

	public function getRows(array $values): array
	{
		$selection = $this->getSelection();
		foreach ($values as $column => $value) {
			$selection->where($column, $value);
		}
		return $selection->fetchAll();
	}
}