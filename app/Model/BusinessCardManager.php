<?php declare(strict_types=1);


namespace App\Model;

use \Nette\Database\Table\Selection;

class BusinessCardManager extends BaseManager
{
	const
		TABLE_NAME = 'business_cards',
		COLUMN_ID = 'ID',
		COLUMN_USERS_ID = 'users_id',
		COLUMN_EMAIL = 'email',
		COLUMN_FUNCTION = 'function',
		COLUMN_COMPANY_NAME = 'companyName',
		COLUMN_COMPANY_ADDRESS = 'companyAddress',
		COLUMN_WEB = 'web',
		COLUMN_TEL1 = 'tel1',
		COLUMN_TEL2 = 'tel2';

	public function getSelection(): Selection
	{
		return $this->database->table(self::TABLE_NAME);
	}

	public function add($userID, $email, $tel1, $tel2, $function, $compName, $compAddress, $web)
	{
		$this->database->table(self::TABLE_NAME)->insert([
			self::COLUMN_USERS_ID => $userID,
			self::COLUMN_EMAIL => $email,
			self::COLUMN_TEL1 => $tel1,
			self::COLUMN_TEL2 => $tel2,
			self::COLUMN_FUNCTION => $function,
			self::COLUMN_COMPANY_NAME => $compName,
			self::COLUMN_COMPANY_ADDRESS => $compAddress,
			self::COLUMN_WEB => $web
		]);
	}

	public function delete($id): void
	{
		$this->database->table(self::TABLE_NAME)->where(self::COLUMN_ID, $id)->delete();
	}

	public function edit($id, $userID, $email, $tel1, $tel2, $function, $compName, $compAddress, $web): void
	{
		$this->database->table(self::TABLE_NAME)->where(self::COLUMN_ID, $id)->update([
			self::COLUMN_USERS_ID => $userID,
			self::COLUMN_EMAIL => $email,
			self::COLUMN_TEL1 => $tel1,
			self::COLUMN_TEL2 => $tel2,
			self::COLUMN_FUNCTION => $function,
			self::COLUMN_COMPANY_NAME => $compName,
			self::COLUMN_COMPANY_ADDRESS => $compAddress,
			self::COLUMN_WEB => $web
		]);
	}
}