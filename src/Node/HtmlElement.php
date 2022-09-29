<?php declare(strict_types=1);

namespace Torr\HtmlBuilder\Node;

use Torr\HtmlBuilder\Exception\InvalidTagNameException;
use Torr\HtmlBuilder\Exception\NoContentAllowedException;
use Torr\HtmlBuilder\Text\SafeMarkup;

final class HtmlElement
{
	private const EMPTY_ELEMENTS = [
		"area" => true,
		"base" => true,
		"br" => true,
		"col" => true,
		"embed" => true,
		"hr" => true,
		"img" => true,
		"input" => true,
		"link" => true,
		"meta" => true,
		"param" => true,
		"source" => true,
		"track" => true,
		"wbr" => true,
	];

	private string $tagName;
	private bool $empty;
	private HtmlAttributes $attributes;
	private array $content = [];

	/**
	 * @param array<self|SafeMarkup|scalar|null> $content
	 */
	public function __construct (
		string $tagName,
		HtmlAttributes|array $attributes = [],
		array $content = [],
	)
	{
		if (!$this->isValidName($tagName))
		{
			throw new InvalidTagNameException(\sprintf("Invalid tag name: '%s'", $tagName));
		}

		$this->tagName = $tagName;
		$this->empty = self::isEmptyElement($tagName);
		$this->attributes = HtmlAttributes::fromValue($attributes);

		foreach ($content as $value)
		{
			$this->append($value);
		}
	}

	/**
	 */
	public function getTagName () : string
	{
		return $this->tagName;
	}

	/**
	 */
	public function getAttributes () : HtmlAttributes
	{
		return $this->attributes;
	}

	/**
	 */
	public function getContent () : array
	{
		return $this->content;
	}

	/**
	 */
	public function isEmpty () : bool
	{
		return $this->empty;
	}

	/**
	 */
	public function getClassList () : ClassList
	{
		return $this->attributes->getClassList();
	}

	/**
	 * @param scalar|self|SafeMarkup|null $value
	 *
	 * @return $this
	 */
	public function append (
		int|float|string|bool|null|self|SafeMarkup $value,
	) : self
	{
		if ($this->empty)
		{
			throw new NoContentAllowedException(\sprintf(
				"Elements of type '%s' can't have content.",
				$this->tagName,
			));
		}

		// ignore `null` content
		if (null === $value)
		{
			return $this;
		}

		if (\is_scalar($value))
		{
			$this->content[] = (string) $value;
			return $this;
		}

		$this->content[] = $value;
		return $this;
	}


	/**
	 * Returns whether an element with the given tag name is empty
	 */
	public static function isEmptyElement (string $tagName) : bool
	{
		return \array_key_exists($tagName, self::EMPTY_ELEMENTS);
	}

	/**
	 * Returns whether the name is valid as HTML tag name
	 */
	private function isValidName (string $name) : bool
	{
		return 0 !== \preg_match('~^[a-z](-?[a-z0-9]+)*$~i', $name);
	}
}
