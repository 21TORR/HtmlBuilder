<?php declare(strict_types=1);

namespace Torr\HtmlBuilder\Node;

use Torr\HtmlBuilder\Exception\InvalidAttributeNameException;
use Torr\HtmlBuilder\Exception\InvalidAttributeValueException;
use Torr\HtmlBuilder\Exception\UnexpectedTypeException;

final class HtmlAttributes
{
	private array $attributes = [];

	public function __construct (array $attributes = [])
	{
		foreach ($attributes as $name => $value)
		{
			$this->set($name, $value);
		}
	}


	/**
	 * Sets an attribute value
	 *
	 * @param scalar $value
	 *
	 * @return $this
	 */
	public function set (string $name, $value) : self
	{
		if (!$this->isValidName($name))
		{
			throw new InvalidAttributeNameException(\sprintf(
				"The attribute name '%s' is invalid.",
				$name
			));
		}

		if (null !== $value && !\is_scalar($value))
		{
			throw new InvalidAttributeValueException(\sprintf(
				"The attribute value of type '%s' is invalid, only scalars and null are allowed.",
				\is_object($value) ? \get_class($value) : \gettype($value)
			));
		}

		$this->attributes[$name] = $value;
		return $this;
	}

	/**
	 * Returns an attribute value
	 *
	 * @param scalar|null $defaultValue
	 * @return scalar|null
	 */
	public function get (string $name, $defaultValue = null)
	{
		return $this->has($name)
			? $this->attributes[$name]
			: $defaultValue;
	}

	/**
	 * Returns whether a certain attribute is set.
	 */
	public function has (string $name) : bool
	{
		return \array_key_exists($name, $this->attributes);
	}

	/**
	 * Returns all attributes.
	 */
	public function all () : array
	{
		return $this->attributes;
	}

	/**
	 * Validates an HTML attribute name
	 *
	 * @see https://www.w3.org/TR/html5/syntax.html#elements-attributes
	 */
	private function isValidName (string $name) : bool
	{
		return "" !== $name
			? !\preg_match('~[ \\x{0000}-\\x{001F}\\x{0080}-\\x{009F}"\'<>/=]~u', $name)
			: false;
	}

	/**
	 * @param self|array $value
	 */
	public static function fromValue ($value) : self
	{
		if ($value instanceof self)
		{
			return $value;
		}

		if (\is_array($value))
		{
			return new self($value);
		}

		throw new UnexpectedTypeException(\sprintf(
			"Can't create attributes from value of type '%s'",
			\is_object($value) ? \get_class($value) : \gettype($value)
		));
	}
}
