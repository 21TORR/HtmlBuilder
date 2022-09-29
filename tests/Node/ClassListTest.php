<?php declare(strict_types=1);

namespace Tests\Torr\HtmlBuilder\Node;

use PHPUnit\Framework\TestCase;
use Torr\HtmlBuilder\Node\ClassList;

final class ClassListTest extends TestCase
{
	/**
	 */
	public function provideInit () : iterable
	{
		yield ["", ""];
		yield ["   ", ""];
		yield ["   a   ", "a"];
		yield ["   a   b   ", "a b"];
		yield ["   a   b as_asd-test-123   ", "a b as_asd-test-123"];
	}

	/**
	 * @dataProvider provideInit
	 */
	public function testInit (string $init, string $expected) : void
	{
		$classList = new ClassList($init);
		self::assertSame($expected, (string) $classList);
	}

	/**
	 */
	public function testInteraction () : void
	{
		$classList = new ClassList();
		self::assertSame("", (string) $classList);

		$classList->add("a", " b ");
		self::assertSame("a b", (string) $classList);

		$classList->remove("b", " c ");
		self::assertSame("a", (string) $classList);

		$classList->toggle("a", " d ");
		self::assertSame("d", (string) $classList);

		$classList->toggle("d");
		self::assertSame("", (string) $classList);

		$classList->set("a", true);
		self::assertSame("a", (string) $classList);

		$classList->set("a", true);
		self::assertSame("a", (string) $classList);

		$classList->set("a", false);
		self::assertSame("", (string) $classList);
	}
}
