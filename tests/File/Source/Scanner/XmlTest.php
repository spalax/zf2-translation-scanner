<?php

require_once 'Translation/File/Source/Scanner/Xml.php';

require_once 'PHPUnit/Framework/TestCase.php';

/**
 * Translation_File_Source_Scanner_Xml test case.
 */
class Test_Translation_File_Source_Scanner_XmlTest extends PHPUnit_Framework_TestCase {
	
	/**
	 * @var Translation_File_Source_Scanner_Xml
	 */
	private $Translation_File_Source_Scanner_Xml;
	
	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {
		parent::setUp ();

		$this->Translation_File_Source_Scanner_Xml = new Translation_File_Source_Scanner_Xml(/* parameters */);
	
	}
	
	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
		$this->Translation_File_Source_Scanner_Xml = null;
		
		parent::tearDown ();
	}
	
	/**
	 * Tests Translation_File_Source_Scanner_Xml->parse()
	 */
	public function testParse() {
		$contents = "
		<description>Translatable \\' ')'\"__;'\"1#</description>
		<label>Translatable ' \")'\"__;'\"2#</label>
		<title>Translatable \\\" \")'\"__;'\"3#</title>
		";
		$strings = $this->Translation_File_Source_Scanner_Xml->parse($contents);
		$this->assertEquals(3, count($strings));
		foreach($strings as $string){
			$this->assertEquals(1, preg_match('/^Translatable.*#$/', $string));
		}
	}

}

