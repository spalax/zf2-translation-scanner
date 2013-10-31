<?php
namespace Zf2TranslationScanner;

class Collector
{
	/**
	 * Search translatable strings in given source 
	 * @param \Iterator $source
	 * @return Word[]
	 */
	public function parse(\Iterator $source)
	{
		$words = array();
        /* @var $file File\Source */
		foreach($source as $file){
			foreach($file->getTranslatableWords() as $word){
				$words[] = new Word($word, '', array($file->getPathname() => array(1)));
			}
		}
		return $words;
	}
}