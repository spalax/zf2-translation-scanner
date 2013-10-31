<?php
namespace Zf2TranslationScanner\Collector\Source;

use Zf2TranslationScanner\FilterIterator\File;
use Zf2TranslationScanner\File\Source;

class Files extends File implements \Iterator {

	/**
	 * List of scanners that should be added to each file by extension
	 * @var array
	 */
	protected $scannersByExtensions = array();
	
	/**
	 * @param \Iterator $iterator
	 * @param array $scannersByExtensions
	 */
	public function __construct(\Iterator $iterator, array $scannersByExtensions = array())
	{
		$this->scannersByExtensions = $scannersByExtensions;

		parent::__construct($iterator, array_keys($scannersByExtensions));
	}
	
	/**
	 * Get current file, add scanners to it and return it
	 * @see FilterIterator::current()
	 * @return Source
	 */
	public function current()
	{
		$file = new Source(parent::current()->getPathName());
		
		$extension = $file->getExtension();
		if(isset($this->scannersByExtensions[$extension])){
			foreach($this->scannersByExtensions[$extension] as $scannerSuffix){
				$scannerClass = ucfirst("Translation_File_Source_Scanner_".$scannerSuffix);
				$file->addScanner(new $scannerClass());
			}
		}
		return $file;
	}
}