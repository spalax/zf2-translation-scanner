<?php
namespace Zf2TranslationScanner;

abstract class SpellCheckerAbstract implements SpellCheckerInterface {
    /**
     * @var array
     */
    protected $misspelledWords = array();

	/**
	 * @return array $misspelledWords
	 */
	public function getMisspelledWords() {
		return $this->misspelledWords;
	}

	/**
	 * @param array $misspelledWords
	 * @return SpellCheckerAbstract
	 */
	public function setMisspelledWords( array $misspelledWords = array()) {
		$this->misspelledWords = $misspelledWords;
		return $this;
	}
	
	/**
	 * @param string $misspelledWord
	 * @return SpellCheckerAbstract
	 */
	public function addMisspelledWord($misspelledWord) {
		if(!in_array($misspelledWord, $this->misspelledWords) && $misspelledWord != ''){
			$this->misspelledWords[] = $misspelledWord;
		}
		return $this;
	}
}