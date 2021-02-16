<?php declare(strict_types=1);

namespace Tests\Torr\HtmlBuilder\Text;

use PHPUnit\Framework\TestCase;
use Torr\HtmlBuilder\Text\SafeMarkup;

final class SafeMarkupTest extends TestCase
{
	/**
	 */
	public function provideCreate () : iterable
	{
		yield ["abc", "abc"];
		yield ["", ""];
		yield [null, ""];
	}

	/**
	 * @dataProvider provideCreate
	 */
	public function testCreate (?string $input, string $expected) : void
	{
		self::assertSame($expected, (new SafeMarkup($input))->getHtml());
	}
}
