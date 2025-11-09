<?php declare(strict_types = 1);

namespace Contributte\Latte\Extensions\Node;

use Generator;
use Latte\Compiler\Nodes\Php\ExpressionNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;

class CdnNode extends StatementNode
{

	public ExpressionNode $path;

	public static function create(Tag $tag): self
	{
		$node = new self();
		$node->path = $tag->parser->parseExpression();
		return $node;
	}

	public function print(PrintContext $context): string
	{
		return $context->format(
			'echo LR\Filters::escapeHtmlAttr(call_user_func($this->global->cdnBuilder, %0.node)) %1.line;',
			$this->path,
			$this->position,
		);
	}

	public function &getIterator(): Generator
	{
		yield $this->path;
	}

}
