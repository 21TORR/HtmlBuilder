<?php declare(strict_types=1);

namespace Tests\Torr\HtmlBuilder\Node;

use PHPUnit\Framework\TestCase;
use Torr\HtmlBuilder\Exception\InvalidAttributeNameException;
use Torr\HtmlBuilder\Node\HtmlAttributes;

final class HtmlAttributesTest extends TestCase
{
	/**
	 */
	public function testAll ()
	{
		$attr = new HtmlAttributes(["a" => 1]);

		self::assertEquals(["a" => 1], $attr->all());

		$attr
			->set("b", 2)
			->set("c", 3);
		self::assertEquals(["a" => 1, "b" => 2, "c" => 3], $attr->all());
	}

	/**
	 */
	public function testDefaultValue ()
	{
		$attr = new HtmlAttributes(["a" => null]);

		self::assertNull($attr->get("missing"));
		self::assertSame(5, $attr->get("missing", 5));

		self::assertNull($attr->get("a"));
		// should keep stored value
		self::assertNull($attr->get("a", "5"));
	}

	public function testSetAndGet ()
	{
		$attr = new HtmlAttributes(["a" => 1]);

		self::assertSame(1, $attr->get("a"));

		$attr->set("a", "test");
		self::assertSame("test", $attr->get("a"));

		$attr->set("a", null);
		self::assertNull($attr->get("a"));
	}

	public function testSetAndGetWithClass ()
	{
		$attr = new HtmlAttributes(["class" => "test   abc"]);

		self::assertSame("test abc", $attr->get("class"));

		$attr->set("class", "test");
		self::assertSame("test", $attr->get("class"));

		$attr->set("class", null);
		self::assertNull($attr->get("class"));
	}

	/**
	 *
	 */
	public function provideInvalidAttributeName () : iterable
	{
		yield "empty string" => [""];
		yield "only space" => [" "];
	}

	/**
	 * @dataProvider provideInvalidAttributeName
	 */
	public function testInvalidAttributeName (mixed $name) : void
	{
		$this->expectException(InvalidAttributeNameException::class);
		new HtmlAttributes([$name => 1]);
	}


	/**
	 */
	public function provideFromValue () : iterable
	{
		yield [[], []];
		yield [["a" => 1], ["a" => 1]];
		yield [new HtmlAttributes(["a" => 1]), ["a" => 1]];
	}

	/**
	 * @dataProvider provideFromValue
	 */
	public function testFromValue ($value, array $expected) : void
	{
		$attrs = HtmlAttributes::fromValue($value);
		self::assertEqualsCanonicalizing($expected, $attrs->all());
	}


	/**
	 */
	public function provideFromValueInvalid () : iterable
	{
		yield [null];
		yield [false];
		yield [1];
		yield ["test"];
	}
}
