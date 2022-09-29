<?php declare(strict_types=1);

namespace Torr\HtmlBuilder\Node;

use Torr\HtmlBuilder\Exception\InvalidAttributeNameException;
use Torr\HtmlBuilder\Exception\InvalidAttributeValueException;
use Torr\HtmlBuilder\Exception\UnexpectedTypeException;

final class HtmlAttributes
{
	private array $attributes = [];
	private ClassList $classList;

	/**
	 */
	public function __construct (array $attributes = [])
	{
		$this->classList = new ClassList();

		foreach ($attributes as $name => $value)
		{
			$this->set($name, $value);
		}
	}


	/**
	 * Sets an attribute value
	 *
	 * @return $this
	 */
	public function set (string $name, int|float|string|bool|null $value) : self
	{
		if (!$this->isValidName($name))
		{
			throw new InvalidAttributeNameException(\sprintf(
				"The attribute name '%s' is invalid.",
				$name
			));
		}

		if ("class" === $name)
		{
			$this->classList = new ClassList((string) $value);
		}

		$this->attributes[$name] = $value;
		return $this;
	}

	/**
	 * Returns an attribute value
	 *
	 * @param scalar|null $defaultValue
	 *
	 * @return scalar|null
	 */
	public function get (
		string $name,
		int|float|string|bool|null $defaultValue = null,
	) : int|float|string|bool|null
	{
		if ("class" === $name)
		{
			return 0 < \count($this->classList)
				? (string) $this->classList
				: null;
		}

		return $this->has($name)
			? $this->attributes[$name]
			: $defaultValue;
	}

	/**
	 * Returns whether a certain attribute is set.
	 */
	public function has (string $name) : bool
	{
		if ("class" === $name)
		{
			return 0 < \count($this->classList);
		}

		return \array_key_exists($name, $this->attributes);
	}

	/**
	 * Returns all attributes.
	 */
	public function all () : array
	{
		$result = $this->attributes;

		if (0 < \count($this->classList))
		{
			$result["class"] = (string) $this->classList;
		}

		return $result;
	}

	/**
	 * Validates an HTML attribute name
	 *
	 * @see https://www.w3.org/TR/html5/syntax.html#elements-attributes
	 */
	private function isValidName (string $name) : bool
	{
		return "" !== $name
			&& !\preg_match('~[ \\x{0000}-\\x{001F}\\x{0080}-\\x{009F}"\'<>/=]~u', $name);
	}

	/**
	 * @param self|array $value
	 */
	public static function fromValue (self|array $value) : self
	{
		return $value instanceof self
			? $value
			: new self($value);
	}

	/**
	 */
	public function getClassList () : ClassList
	{
		return $this->classList;
	}
}
