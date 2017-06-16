<?php

namespace Contributte\Latte;

use Nette\Application\UI\Control;
use Nette\Bridges\ApplicationLatte\Template;
use Nette\Bridges\ApplicationLatte\TemplateFactory as NTemplateFactory;

class TemplateFactory extends NTemplateFactory
{

	/** @var callable[]  function (Template $template, Control $control); Occurs during template creating */
	public $onCreate = [];

	/**
	 * @param Control|NULL $control
	 * @return Template
	 */
	public function createTemplate(Control $control = NULL)
	{
		$template = parent::createTemplate($control);

		$this->onCreate($template, $control);

		return $template;
	}

}
