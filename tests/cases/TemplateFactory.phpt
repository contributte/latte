<?php

/**
 * Test: TemplateFactory
 */

use Contributte\Latte\TemplateFactory;
use Nette\Application\UI\Control;
use Nette\Bridges\ApplicationLatte\Template;
use Ninjify\Nunjuck\Notes;
use Tester\Assert;
use Tests\Fixtures\LatteFactory;

require_once __DIR__ . '/../bootstrap.php';

test(function () {
	$latteFactory = new LatteFactory();
	$templateFactory = new TemplateFactory($latteFactory);

	$templateFactory->onCreate[] = function (Template $template, Control $control = NULL) {
		Notes::add('fired');
	};

	$templateFactory->createTemplate();
	Assert::equal(['fired'], Notes::fetch());
});
