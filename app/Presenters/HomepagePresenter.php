<?php declare(strict_types=1);


namespace App\Presenters;

use App\Components\BusinessCardControl;
use App\Model\BusinessCardManager;
use Nette\Application\UI\Form;
use Nette\Application\UI\Multiplier;
use Nette\ComponentModel\IComponent;
use Nette\Database\Table\ActiveRow;


final class HomepagePresenter extends BasePresenter
{
	protected BusinessCardManager $businessCardManager;

	public function __construct(BusinessCardManager $businessCardManager)
	{
		parent::__construct($businessCardManager);
		$this->businessCardManager = $businessCardManager;
	}

	public function startup()
	{
		parent::startup();
	}

	public function renderDefault()
	{
		$this->template->businessCards = $this->businessCardManager->getRows([BusinessCardManager::COLUMN_USERS_ID => $this->getUser()->getId()]);
	}

	public function handleNewBusinessCard(): void
	{
		$this->redirect('BusinessCard:New');
	}

	public function handleEditBusinessCard($id): void
	{
		$this->redirect('BusinessCard:edit', ['id' => $id]);
	}

	public function handleDeleteBusinessCard($id): void
	{
		$this->businessCardManager->delete($id);
		$this->flashMessage('Vizitka smazána');
		$this->redirect('this');
	}

	public function createComponentBusinessCards(): Multiplier
	{
		return new Multiplier(function ($id) {
			return new BusinessCardControl($this->template->businessCards[$id]);
		});
	}
}
