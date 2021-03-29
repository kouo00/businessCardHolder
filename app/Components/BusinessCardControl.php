<?php declare(strict_types=1);


namespace App\Components;

use Nette\Application\UI\Control;
use Nette\Database\Table\ActiveRow;


class BusinessCardControl extends Control
{
	public function render(ActiveRow $values)
	{
		$this->template->setFile(__DIR__ . '/businessCard.latte');
		$this->template->values = $values;
		$this->template->render();
	}
}