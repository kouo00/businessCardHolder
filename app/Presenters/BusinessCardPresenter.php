<?php declare(strict_types=1);


namespace App\Presenters;

use App\Model\BusinessCardManager;
use Nette\Application\UI\Form;

class BusinessCardPresenter extends BasePresenter
{
	protected BusinessCardManager $businessCardManager;

	public function __construct(BusinessCardManager $businessCardManager)
	{
		parent::__construct($businessCardManager);

	}

	public function startup()
	{
		parent::startup();
	}

	public function actionEdit($id): void
	{
		$businesCardRow = $this->businessCardManager->getRow([BusinessCardManager::COLUMN_ID => $this->getParameter('id')]);
		if ($businesCardRow->users_id != $this->getUser()->getId()){
			$this->redirect('Homepage:');
		}
	}

	public function createComponentNewBusinessCardForm(): Form
	{
		$form = new Form();
		$form->addEmail('email', 'Email')->addRule(Form::EMAIL)->setDefaultValue($this->getUser()->getIdentity()->getData()['email']);
		$form->addText('tel1', 'Tel.')->addRule(Form::FILLED);
		$form->addText('tel2', 'Tel. 2')->setOption('id', 'tel2')->setHtmlAttribute('placeholder', 'nepovinné');
		$form->addText('function', 'Funkce ve společnosti')->addRule(Form::FILLED);
		$form->addText('companyName', 'Název společnosti')->addRule(Form::FILLED);
		$form->addText('companyAddress', 'Adresa')->addRule(Form::FILLED);
		$form->addText('web', 'Webové stránky')->setHtmlAttribute('placeholder', 'nepovinné');
		$form->addSubmit('submit', 'Vytvořit')->onClick[] = [$this, 'newBusinessCardFormSucceeded'];
		return $form;
	}

	public function newBusinessCardFormSucceeded(\stdClass $values): void
	{
		$this->businessCardManager->add(
			$this->user->getId(), $values->email,
			$values->tel1, $values->tel2, $values->function,
			$values->companyName, $values->companyAddress, $values->web
		);
		$this->flashMessage('Vizitka vytvořena');
		$this->redirect('Homepage:');
	}

	public function createComponentEditBusinessCardForm(): Form
	{
		$form = new Form();
		$form->addHidden('id', $this->getParameter('id'));
		$form->addEmail('email', 'Email')->addRule(Form::EMAIL);
		$form->addText('tel1', 'Tel.')->addRule(Form::FILLED);
		$form->addText('tel2', 'Tel. 2')->setOption('id', 'tel2')->setHtmlAttribute('placeholder', 'nepovinné');
		$form->addText('function', 'Funkce ve společnosti')->addRule(Form::FILLED);
		$form->addText('companyName', 'Název společnosti')->addRule(Form::FILLED);
		$form->addText('companyAddress', 'Adresa')->addRule(Form::FILLED);
		$form->addText('web', 'Webové stránky')->setHtmlAttribute('placeholder', 'nepovinné');
		$form->addSubmit('submit', 'Uložit');
		$form->onSuccess[] = [$this, 'editBusinessCardFormSucceeded'];

		$businessCard = $this->businessCardManager->getRow([BusinessCardManager::COLUMN_ID => $this->getParameter('id')]);
		if (!empty($businessCard))
		{
			$form->setDefaults([
				'email' => $businessCard->email,
				'tel1' => $businessCard->tel1,
				'tel2' => $businessCard->tel2,
				'function' => $businessCard->function,
				'companyName' => $businessCard->companyName,
				'companyAddress' => $businessCard->companyAddress,
				'web' => $businessCard->web,
			]);
		}

		return $form;
	}

	public function editBusinessCardFormSucceeded(Form $form, \stdClass $values): void
	{
		$this->businessCardManager->edit(
			$this->getParameter('id'), $this->user->getId(), $values->email, $values->tel1, $values->tel2,
			$values->function, $values->companyName, $values->companyAddress, $values->web
		);
		$this->flashMessage('Vizitka uložena');
		$this->redirect('Homepage:');
	}
}