<?php declare(strict_types=1);


namespace App\Components;

use App\Model\BusinessCardManager;
use Nette\Application\UI\Control;


class BusinessCardControl extends Control
{
	private BusinessCardManager $businessCardManager;

	public function __construct(BusinessCardManager $businessCardManager)
	{
		$this->businessCardManager = $businessCardManager;
	}

	public function render()
	{
		$this->template->setFile(__DIR__ . '/businessCard.latte');

		foreach ($this->getValues() as $businessCard){
			$this->template->values = $businessCard;
			$this->template->render();
		}
	}

	public function handleEditBusinessCard($id): void
	{
		$this->getPresenter()->redirect('BusinessCard:edit', ['id' => $id]);
	}

	public function handleDeleteBusinessCard($id): void
	{
		$this->businessCardManager->delete($id);
		$this->flashMessage('Vizitka smazána');
		$this->redirect('this');
	}

	private function getValues(){
		return $this->businessCardManager->getRows([BusinessCardManager::COLUMN_USERS_ID => $this->getPresenter()->getUser()->getId()]);
	}
}