<?php declare(strict_types=1);

namespace Torr\HtmlBuilder\Text;

/**
 * A class that marks markup as "safe HTML", that doesn't need to be escaped.
 */
final class SafeMarkup
{
	private string $html;

	public function __construct (?string $html)
	{
		$this->html = (string) $html;
	}

	/**
	 */
	public function getHtml () : string
	{
		return $this->html;
	}
}
