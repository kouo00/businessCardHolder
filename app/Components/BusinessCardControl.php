<?php


namespace App\Components;

use Nette\Application\UI\Control;


class BusinessCardControl extends Control
{
	public function render($values)
	{
		$this->template->setFile(__DIR__ . '/businessCard.latte');
		$this->template->values = $values;
		$this->template->render();
	}
}