<?php
namespace Zf2TranslationScanner;

class WordsContainer implements \ArrayAccess, \Countable{

	/**
	 * Array to contain Words
     *
	 * @var Word[]
	 */
	protected $words = array();

	/**
	 * @param array $words
	 */
	public function __construct(array $words = array())
	{
		$this->addWords($words);
	}

	/* (non-PHPdoc)
	 * @see ArrayAccess::offsetExists()
	 */
	public function offsetExists($offset)
    {
		return isset($this->words[$offset]);
	}

	/**
	 * @see ArrayAccess::offsetGet()
	 * @return Word
	 */
	public function offsetGet($offset)
    {
		return $this->words[$offset];
	}

	/* (non-PHPdoc)
	 * @see ArrayAccess::offsetSet()
	 */
	public function offsetSet($offset, $value)
    {
		if(! $value instanceof Word) {
			throw new \InvalidArgumentException('Words container must receive objects of Word');
		}
		$this->addWord($value);
	}

	/* (non-PHPdoc)
	 * @see ArrayAccess::offsetUnset()
	 */
	public function offsetUnset($offset)
    {
		unset($this->words[$offset]);
	}

	/* (non-PHPdoc)
	 * @see Countable::count()
	 */
	public function count()
    {
		return count($this->words);
	}

	/**
	 * @return array
	 */
	public function getValues()
	{
		return $this->words;
	}

	/**
	 * Add word to container
	 * @param Word $word
	 * @return WordsContainer
	 */
	public function addWord(Word $word)
	{
		if(!isset($this->words[$word->getText()])){
			$this->words[$word->getText()] = $word;
		}else{
			foreach($word->getOccurrences() as $file => $lines){
				foreach($lines as $line){
					$this->words[$word->getText()]->addOccurrence($file, $line);
				}
			}
		}
		return $this;
	}

	/**
	 * Add words to container
	 * @param Array $word
	 * @return WordsContainer
	 */
	public function addWords(array $words)
	{
		foreach($words as $word){
			$this->addWord($word);
		}
		return $this;
	}

	/**
	 * @param Word $word
	 * @return WordsContainer
	 */
	public function deleteWord(Word $word)
	{
		unset($this->words[$word->getText()]);
		return $this;
	}

	/**
	 * Check whether given is present in the container
	 * @param Word $word
	 * @return boolean
	 */
	public function hasWord(Word $word)
	{
		return isset($this->words[$word->getText()]);
	}

	/**
	 * Get words that occured in given source file
	 * @param string $fileName
	 * @return multitype:
	 */
	public function getWordsFromFile($fileName)
	{
		$words = array();
        foreach($this->words as $word){
			if($word->occurredInFile($fileName)){
				array_push($words, $word);
			}
		}
		return $words;
	}

	/**
	 * Check whether this container has words from given source file
	 * @param string $fileName
	 * @return boolean
	 */
	public function hasWordsFromFile($fileName)
	{
		$has = false;
		foreach($this->words as $word){
			if($word->occurredInFile($fileName)){
				$has = true;
				break;
			}
		}
		return $has;
	}


	/**
	 * Get all files that contain words from this container filtered by extentioins
	 * @param Array $extensions
	 * @return Array
	 */
	public function getFiles($extensions = array())
	{
		$allFiles = array();
		foreach($this->words as $word){
			foreach($word->getOccurrences() as $file => $lines){
				if(!empty($extensions)){
					if(preg_match('/\.([a-z0-9A-Z]*)$/', $file, $matches)
						&& in_array($matches[1], $extensions)){

						$allFiles[$file] = $file;
					}
				}else{
					$allFiles[$file] = $file;
				}
			}
		}
		return $allFiles;
	}

	/**
	 * Return number of source files that contain words from the container
	 * @return number
	 */
	public function countFiles()
	{
		$allFiles = array();
		foreach($this->words as $word){
			foreach($word->getOccurrences() as $file => $lines){
				$allFiles[$file] = 1;
			}
		}
		return count($allFiles);
	}

	/**
	 * Return the number of words from this container
	 * @return number
	 */
	public function countWords()
	{
		return $this->count();
	}

	/**
	 * Check Spelling of words in this container
     *
	 * @param SpellCheckerInterface $spellChecker
	 */
	public function checkSpelling(SpellCheckerInterface $spellChecker)
    {
		foreach($this->words as $word) {
			if(!$spellChecker->isValid($word->getText())){
				$word->setIsMisspelled(true);
			}
		}
	}
}