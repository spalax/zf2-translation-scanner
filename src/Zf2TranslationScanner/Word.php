<?php
namespace Zf2TranslationScanner;

class Word extends \ArrayObject{

	/**
	 * @var string
	 */
	protected $text;
	
	/**
	 * @var string
	 */
	protected $translation;
	
	/**
	 * Is properly spelled?
	 * @var boolean
	 */
	protected $isMisspelled = false;
	
	/**
	 * List of occurrences ( array('fileName'=> array(1,2,3) ) )
	 * @var Array
	 */
	protected $occurrences = array();
	
	/**
	 * @param string $text
	 * @param string $translation
	 * @param Array $occurrences
	 * @param boolean $isMisspelled
	 */
	public function __construct($text, $translation = '', array $occurrences = array(), $isMisspelled = false)
	{
		$this->text = $text;
		$this->translation = $translation;
		$this->occurrences = $occurrences;
		$this->isMisspelled = $isMisspelled;
	}
	/**
	 * @return string
	 */
	public function getText()
    {
		return $this->text;
	}

	/**
	 * @return string
	 */
	public function getTranslation()
    {
		return $this->translation;
	}

	/**
	 * @return boolean
	 */
	public function getIsMisspelled()
    {
		return $this->isMisspelled;
	}

	/**
	 * @return Array
	 */
	public function getOccurrences()
    {
		return $this->occurrences;
	}

	/**
	 * @param string $text
	 * @return Word
	 */
	public function setText($text)
    {
		$this->text = $text;
		return $this;
	}

	/**
	 * @param string $translation
	 * @return Word
	 */
	public function setTranslation($translation) {
		$this->translation = $translation;
		return $this;
	}

	/**
	 * @param boolean $isMisspelled
	 * @return Word
	 */
	public function setIsMisspelled($isMisspelled = true) {
		$this->isMisspelled = $isMisspelled;
		return $this;
	}

	/**
	 * @param Array $_occurrences
	 * @return Word
	 */
	public function setOccurrences(array $occurrences = array()) {
		$this->occurrences = $occurrences;
		return $this;
	}
	
	/**
	 * Add occurrence in file.
	 * @param string $fileName
	 * @param number $line
	 * @return Word
	 */
	public function addOccurrence($fileName, $line) {
		if(!isset($this->occurrences[$fileName])){
			$this->occurrences[$fileName] = array();
		}
		$this->occurrences[$fileName][] = $line;
		return $this;
	}
	
	/**
	 * Check whether this word occurred in given file
	 * @param string $fileName
	 * @return boolean
	 */
	public function occurredInFile($fileName)
	{
		return isset($this->_occurrences[$fileName]);
	}
	
	
	/**
	 * Return word text if treated like string
	 * @return string
	 */
	public function __toString()
	{
		return $this->getText();
	}
}