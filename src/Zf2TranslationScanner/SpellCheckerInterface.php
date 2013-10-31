<?php
namespace Zf2TranslationScanner;

interface SpellCheckerInterface
{
	/**
	 * Check spelling of given phrase
	 * @param string $phrase
	 */
	public function isValid($phrase);
}