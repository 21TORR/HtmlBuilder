<?php declare(strict_types=1);

namespace Torr\HtmlBuilder\Node;

final class ClassList implements \Stringable, \Countable
{
	/**
	 *
	 */
	private array $list = [];

	/**
	 */
	public function __construct (string $classes = "")
	{
		foreach (\preg_split("~\s+~", $classes) as $class)
		{
			$this->add($class);
		}
	}

	/**
	 * @return $this
	 */
	public function add (string ...$classes) : self
	{
		foreach ($classes as $class)
		{
			$class = \trim($class);

			if ("" === $class)
			{
				continue;
			}

			$this->list[$class] = true;
		}

		return $this;
	}

	/**
	 * @return $this
	 */
	public function remove (string ...$classes) : self
	{
		foreach ($classes as $class)
		{
			$class = \trim($class);

			if ("" === $class)
			{
				continue;
			}

			$this->list[$class] = false;
		}

		// remove false values
		$this->list = \array_filter($this->list);
		return $this;
	}

	/**
	 */
	public function contains (string $class) : bool
	{
		$class = \trim($class);
		return $this->list[$class] ?? false;
	}

	/**
	 * @return $this
	 */
	public function set (string $class, bool $add) : self
	{
		return $add
			? $this->add($class)
			: $this->remove($class);
	}

	/**
	 * @return $this
	 */
	public function toggle (string ...$classes) : self
	{
		foreach ($classes as $class)
		{
			$this->set(
				$class,
				!$this->contains($class),
			);
		}

		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function __toString () : string
	{
		$onlyActive = \array_filter($this->list);
		return \implode(" ", \array_keys($onlyActive));
	}

	/**
	 * @inheritDoc
	 */
	public function count () : int
	{
		return \count($this->list);
	}
}
