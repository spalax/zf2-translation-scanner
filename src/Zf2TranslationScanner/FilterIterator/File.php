<?php
namespace Zf2TranslationScanner\FilterIterator;

class File extends \FilterIterator
{

    /**
     * List of allowed file extensions
     * @var array
     */
    private $extensions = array();

    /**
     * @param \Iterator $iterator
     * @param array $extensions
     */
    public function __construct(\Iterator $iterator, array $extensions = array())
    {
        parent::__construct($iterator);

        $this->extensions = $extensions;
    }

    /**
     *
     * @link  http://www.php.net/manual/en/filteriterator.accept.php
     * @return  bool true if the current element is acceptable, otherwise false.
     * @see FilterIterator::accept()
     */
    public function accept()
    {
        if ($this->getInnerIterator()->current()->isFile()) {
            if (!empty($this->extensions)) {
                $_extArray = explode('.', $this->getInnerIterator()->current()->GetFilename());
                return in_array(array_pop($_extArray), $this->extensions);
            } else {
                return true;
            }
        }
        return false;
    }
}