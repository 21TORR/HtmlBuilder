<?php declare(strict_types=1);

namespace Torr\HtmlBuilder\Builder;

use Torr\HtmlBuilder\Node\HtmlAttributes;
use Torr\HtmlBuilder\Node\HtmlElement;
use Torr\HtmlBuilder\Text\SafeMarkup;

final class HtmlBuilder
{
	/**
	 * Builds the HTML element
	 */
	public function build (HtmlElement $element) : string
	{
		$html = ["<{$element->getTagName()}"];
		$attrs = $this->buildAttributes($element->getAttributes());

		if ("" !== $attrs)
		{
			$html[] = " {$attrs}";
		}

		$html[] = ">";

		foreach ($element->getContent() as $content)
		{
			$html[] = $this->buildContent($content);
		}

		if (!$element->isEmpty())
		{
			$html[] = "</{$element->getTagName()}>";
		}

		return \implode("", $html);
	}

	/**
	 * Builds the HTML attributes
	 */
	private function buildAttributes (HtmlAttributes $attributes) : string
	{
		$result = [];

		foreach ($attributes->all() as $key => $value)
		{
			if (null === $value || false === $value)
			{
				continue;
			}

			if (true === $value)
			{
				$result[] = $key;
				continue;
			}

			$result[] = \sprintf('%s="%s"', $key, \htmlspecialchars((string) $value, \ENT_COMPAT));
		}

		return \implode(" ", $result);
	}


	/**
	 * Builds the content
	 */
	private function buildContent (
		SafeMarkup|float|HtmlElement|bool|int|string|null $content,
	) : string
	{
		if ($content instanceof SafeMarkup)
		{
			return $content->getHtml();
		}

		if ($content instanceof HtmlElement)
		{
			return $this->build($content);
		}

		return \htmlspecialchars((string) $content, \ENT_NOQUOTES);
	}
}
