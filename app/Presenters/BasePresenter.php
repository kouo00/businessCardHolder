<?php


namespace App\Presenters;

use App\Model\BusinessCardManager;
use Nette\Application\UI\Presenter;

class BasePresenter extends Presenter
{
	protected BusinessCardManager $businessCardManager;

	public function __construct(BusinessCardManager $businessCardManager)
	{
		parent::__construct();
		$this->businessCardManager = $businessCardManager;
	}

	protected function startup()
	{
		parent::startup();

		if ($this->getUser()->isLoggedIn()){
			$user = $this->getUser()->getIdentity();
			$this->template->user = $user;

			if ($this->name == 'Login') {
				$this->redirect('Homepage:');
			}
		}
		else{
			if ($this->name != 'Login') {
				$this->redirect('Login:');
			}
		}
	}

	public function handleLogout(): void
	{
		$this->user->logout(true);
		$this->flashMessage('Uživatel odhlášen');
		$this->redirect('Login:');
	}
}