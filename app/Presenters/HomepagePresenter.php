<?php declare(strict_types=1);


namespace App\Presenters;

use App\Components\BusinessCardControl;
use App\Model\BusinessCardManager;
use Nette\ComponentModel\IComponent;


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

	public function handleNewBusinessCard(): void
	{
		$this->redirect('BusinessCard:New');
	}

	public function createComponentBusinessCards(): IComponent
	{
		return new BusinessCardControl($this->businessCardManager);
	}
}
