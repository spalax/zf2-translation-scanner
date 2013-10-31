<?php
namespace Zf2TranslationScanner\File\Source;

abstract class ScannerAbstract implements ScannerInterface {

	/**
	 * Find all occurrences of strings matching $regexp in $content
	 *
	 * @param string $regexp Regular expression for scanner
	 * @param string $content
	 * @return array $matches
	 */
	protected function _scanForConstruction($regexp, $content)
	{
		$matches = array();
		preg_match_all($regexp, $content, $matches);
		return isset($matches[1])?$matches[1]:array();
	}
}