<?php declare(strict_types=1);


namespace App\Model;

use Nette\Database\Explorer;
use Nette\Database\Table\Selection;
use Nette\Security\Passwords;
use Nette\Security\SimpleIdentity;
use Nette\Security\AuthenticationException;
use Nette\Security\Authenticator;

class UserManager extends BaseManager implements Authenticator
{
	const
		TABLE_NAME = 'users',
		COLUMN_ID = 'id',
		COLUMN_NAME = 'name',
		COLUMN_EMAIL = 'email',
		COLUMN_PASSWORD = 'password';

	private Passwords $passwords;

	public function __construct(Explorer $database, Passwords $passwords) {
		parent::__construct($database);
		$this->passwords = $passwords;
	}

	function authenticate(string $user, string $password): SimpleIdentity
	{
		$row = $this->database->table('users')
			->where(self::COLUMN_EMAIL, $user)
			->fetch();

		if (!$row) {
			throw new AuthenticationException('Uživatel nenalezen.');
		}

		if (!$this->passwords->verify($password, $row->password)) {
			throw new AuthenticationException('Chybné heslo.');
		}

		return new SimpleIdentity(
			$row->id,
			null,
			[
				'name' => $row->name,
				'email' => $row->email,
				'loggedIn' => true]
		);
	}

	public function getSelection(): Selection
	{
		return $this->database->table(self::TABLE_NAME);
	}

	public function add($name, $email, $password): void
	{
		if (!$this->alreadyRegistered($email))
		{
			$password = password_hash($password, PASSWORD_BCRYPT);

			$this->database->table(self::TABLE_NAME)->insert([
				self::COLUMN_NAME => $name,
				self::COLUMN_EMAIL => $email,
				self::COLUMN_PASSWORD => $password
			]);
		}
		else{
			throw new \Exception('Tento email je obsazený!');
		}
	}

	private function alreadyRegistered($email): bool
	{
		if ($this->getRows([self::COLUMN_EMAIL => $email]) == null)
		{
			return false;
		}
		else{
			return true;
		}
	}
}