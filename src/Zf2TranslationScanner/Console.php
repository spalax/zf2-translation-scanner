<?php
namespace Zf2TranslationScanner;

use Zf2Libs\Filter\Formatter;

class Console/* extends Zend_Console_Getopt*/  {

	/**
	 * Internal print function
	 *
	 * @param string $template
	 * @param array $data data for place holders
	 * @param boolean $new_line
	 * @param boolean $exit is execution must be stopped after print
	 * @return void
	 */
	public function _print($template = "", $data=array(), $new_line = true, $exit = false)
	{
		if ($this->v) {
            $filter = new Formatter($template.($new_line ? "\n" : ""));
            print $filter->filter($data);
		}
		if ($exit === true) exit(0);
	}
}