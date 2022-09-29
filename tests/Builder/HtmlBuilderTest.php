<?php declare(strict_types=1);

namespace Tests\Torr\HtmlBuilder\Builder;

use PHPUnit\Framework\TestCase;
use Torr\HtmlBuilder\Builder\HtmlBuilder;
use Torr\HtmlBuilder\Node\HtmlElement;
use Torr\HtmlBuilder\Text\SafeMarkup;

final class HtmlBuilderTest extends TestCase
{

	public function provideBuild () : iterable
	{
		yield [
			new HtmlElement("div"),
			'<div></div>',
		];

		yield [
			new HtmlElement("img"),
			'<img>',
		];

		yield [
			new HtmlElement(
				"div",
				[
					"a" => "test",
					"b" => "asdas'asd",
					"c" => true,
					"d" => false,
					"e" => null,
					"f" => "final",
					"class" => "test  abc ",
				],
				[
					"test ",
					123,
					" <b>b</b> ",
					new SafeMarkup("<strong>strong</strong>"),
					" an element → ",
					new HtmlElement("img", ["src" => "123"]),
					" ←",
				]
			),
			'<div a="test" b="asdas\'asd" c f="final" class="test abc">test 123 &lt;b&gt;b&lt;/b&gt; <strong>strong</strong> an element → <img src="123"> ←</div>',
		];
	}

	/**
	 * @dataProvider provideBuild
	 */
	public function testBuild (HtmlElement $element, string $expected)
	{
		self::assertSame($expected, (new HtmlBuilder())->build($element));
	}
}
