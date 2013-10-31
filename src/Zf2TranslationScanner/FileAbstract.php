<?php
namespace Zf2TranslationScanner;

abstract class FileAbstract extends \SplFileInfo {

	/**
	 * Get content of the file
	 * @return string
	 */
	public function getExtension()
	{
		$parts = explode('.', $this->getFilename());
		return array_pop($parts);
	}
	
	/**
	 * Get content of the file
	 * @return string
	 */
	public function getContents()
	{
		return file_get_contents($this->getPathname());
	}
	
	
	/**
	 * Method to build given directory structure;
	 * Return true when done of if directory exists
	 * @param string $dir
	 * @return boolean
	 */
	protected function createDirectoryStructure($dir)
	{
		if(file_exists($dir) || ($this->createDirectoryStructure(dirname($dir)) && mkdir($dir))){
			return true;
		}else{
			return false;
		}
	}
}