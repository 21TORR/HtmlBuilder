<?php declare(strict_types=1);

namespace Tests\Torr\HtmlBuilder\Node;

use PHPUnit\Framework\TestCase;
use Torr\HtmlBuilder\Exception\InvalidAttributeNameException;
use Torr\HtmlBuilder\Exception\InvalidAttributeValueException;
use Torr\HtmlBuilder\Exception\UnexpectedTypeException;
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
		self::assertSame(null, $attr->get("a"));
	}

	/**
	 *
	 */
	public function testInvalidAttributeName ()
	{
		$this->expectException(InvalidAttributeNameException::class);
		new HtmlAttributes([" " => 1]);
	}

	/**
	 *
	 */
	public function testInvalidAttributeValue ()
	{
		$this->expectException(InvalidAttributeValueException::class);
		new HtmlAttributes(["a" => new \stdClass()]);
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

	/**
	 * @dataProvider provideFromValueInvalid
	 */
	public function testFromValueInvalid ($value) : void
	{
		$this->expectException(UnexpectedTypeException::class);
		HtmlAttributes::fromValue($value);
	}
}
