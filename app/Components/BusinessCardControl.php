<?php declare(strict_types=1);


namespace App\Components;

use App\Model\BusinessCardManager;
use Nette\Application\UI\Control;
use Nette\Database\Table\ActiveRow;


class BusinessCardControl extends Control
{
	private ActiveRow $businessCard;

	public function __construct(ActiveRow $businessCard)
	{
		$this->businessCard = $businessCard;
	}

	public function render()
	{
		$this->template->setFile(__DIR__ . '/businessCard.latte');
			$this->template->values = $this->businessCard;
			$this->template->render();
	}
}