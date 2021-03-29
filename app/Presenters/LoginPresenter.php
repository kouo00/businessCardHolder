<?php declare(strict_types=1);


namespace App\Presenters;

use App\Model\UserManager;
use Exception;
use Nette;
use Nette\Application\UI\Form;

class LoginPresenter extends BasePresenter
{
	private UserManager $userManager;

	public function __construct(UserManager $userManager)
	{
		$this->userManager = $userManager;
	}

	public function startup()
	{
		parent::startup();
	}

	protected function createComponentLoginForm(): Form
	{
		$form = new Form;
		$form->addText('email', 'E-mail: ', 35)
			->setEmptyValue('@')
			->addRule(Form::EMAIL, 'Neplatná emailová adresa');
		$form->addPassword('password', 'Heslo: ', 20)
			->addRule(Form::FILLED, 'Vyplňte Vaše heslo');
		$form->addSubmit('login', 'Přihlásit se')
			->onClick[] = [$this, 'loginFormSucceeded'];
		$form->addSubmit('register', 'Registrovat se')
			->setValidationScope([])
			->onClick[] = [$this, 'loginFormRegister'];
		return $form;
	}

	public function loginFormSucceeded(Form $form, \stdClass $values): void
	{
		try {
			$this->getUser()->login($values->email, $values->password);
			$this->redirect('Homepage:');
		} catch (Nette\Security\AuthenticationException $e) {
			$form->addError('Nesprávné přihlašovací jméno nebo heslo.');
		}
	}

	public function loginFormRegister(): void
	{
		$this->redirect('Login:registration');
	}

	protected function createComponentRegisterForm(): Form
	{
		$form = new Form;
		$form->addText('email', 'E-mail: *', 35)
			->setEmptyValue('@')
			->addRule(Form::FILLED, 'Vyplňte Váš email')
			->addCondition(Form::FILLED)
			->addRule(Form::EMAIL, 'Neplatná emailová adresa');
		$form->addText('name','Jméno: ',20);
		$form->addPassword('password', 'Heslo: *', 20)
			->setOption('description', 'Alespoň 6 znaků')
			->addRule(Form::FILLED, 'Vyplňte Vaše heslo')
			->addRule(Form::MIN_LENGTH, 'Heslo musí mít alespoň %d znaků.', 6);
		$form->addPassword('password2', 'Heslo znovu: *', 20)
			->addConditionOn($form['password'], Form::VALID)
			->addRule(Form::FILLED, 'Heslo znovu')
			->addRule(Form::EQUAL, 'Hesla se neshodují.', $form['password']);
		$form->addSubmit('send', 'Registrovat');
		$form->onSuccess[] = [$this, 'registerFormSucceeded'];
		return $form;
	}

	public function registerFormSucceeded(Form $form, \stdClass $values): void
	{
		try {
			$this->userManager->add($values->name, $values->email, $values->password);
			$this->flashMessage('Účet vytvořen!');
			$this->redirect('Login:default');
		} catch (Exception $e) {
			$this->flashMessage($e->getMessage());
			$this->redirect('this');
		}
	}
}