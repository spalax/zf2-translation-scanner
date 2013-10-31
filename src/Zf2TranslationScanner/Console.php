<?php
namespace Zf2TranslationScanner;

use Zend\Console\Getopt;
use Zf2Libs\Filter\Formatter;

class Console extends Getopt
{
    /**
     * Internal print function
     *
     * @param string $template
     * @param array $data data for place holders
     * @param boolean $new_line
     * @param boolean $exit is execution must be stopped after print
     * @return void
     */
    public function log($template = "", $data = array(), $new_line = true, $exit = false)
    {
        if ($this->v) {
            $filter = new Formatter($template . ($new_line ? "\n" : ""));
            print $filter->filter($data);
        }
        if ($exit === true) exit(0);
    }

    /**
     * Show Error message
     *
     * @param string $template
     * @param array $data
     */
    public function error($template = "", $data = array())
    {
        $this->log("[ERROR] ".$template, $data, true, true);
    }
}