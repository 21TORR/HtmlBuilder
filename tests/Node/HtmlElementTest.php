<?php declare(strict_types=1);

namespace Tests\Torr\HtmlBuilder\Node;

use PHPUnit\Framework\TestCase;
use Torr\HtmlBuilder\Exception\InvalidTagNameException;
use Torr\HtmlBuilder\Exception\NoContentAllowedException;
use Torr\HtmlBuilder\Node\HtmlAttributes;
use Torr\HtmlBuilder\Node\HtmlElement;
use Torr\HtmlBuilder\Text\SafeMarkup;

final class HtmlElementTest extends TestCase
{
	/**
	 */
	public function testCreate ()
	{
		$attr = new HtmlAttributes();
		$markup = new SafeMarkup("test");

		$element = new HtmlElement("div", $attr, [$markup]);
		self::assertSame($attr, $element->getAttributes());
		self::assertSame([$markup], $element->getContent());
	}

	/**
	 *
	 */
	public function testAddContent ()
	{
		$element = new HtmlElement("div");
		self::assertEquals([], $element->getContent());

		$element->append("test");
		self::assertEquals(["test"], $element->getContent());

		$element->append(null);
		self::assertEquals(["test"], $element->getContent());

		$element->append(1);
		self::assertEquals(["test", 1], $element->getContent());

		$markup = new SafeMarkup("a");
		$element->append($markup);
		self::assertEquals(["test", 1, $markup], $element->getContent());

		$anotherElement = new HtmlElement("a");
		$element->append($anotherElement);
		self::assertEquals(["test", 1, $markup, $anotherElement], $element->getContent());
	}

	/**
	 */
	public function provideInvalidTagName () : iterable
	{
		yield ["a b"];
		yield [""];
		yield ["-a"];
		yield ["a-"];
		yield ["9a"];
	}

	/**
	 * @dataProvider provideInvalidTagName
	 */
	public function testInvalidTagName (string $tagName) : void
	{
		$this->expectException(InvalidTagNameException::class);
		new HtmlElement($tagName);
	}

	/**
	 */
	public function provideEmpty () : iterable
	{
		yield ["h6", false];
		yield ["hr", true];
		yield ["br", true];
		yield ["img", true];
	}

	/**
	 * @dataProvider provideEmpty
	 */
	public function testEmpty (string $tagName, bool $shouldBeEmpty) : void
	{
		if ($shouldBeEmpty)
		{
			$this->expectException(NoContentAllowedException::class);
		}

		$element = new HtmlElement($tagName, [], ["test"]);

		if (!$shouldBeEmpty)
		{
			self::assertInstanceOf(HtmlElement::class, $element);
		}
	}
}
