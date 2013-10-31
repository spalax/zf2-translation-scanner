<?php
/**
 * Translation_SpellChecker_Aspell test case.
 */
require_once 'Translation/SpellChecker/Aspell.php';

require_once 'PHPUnit/Framework/TestCase.php';
class Test_Translation_SpellChecker_AspellTest extends PHPUnit_Framework_TestCase {
	
	/**
	 * @var Translation_SpellChecker_Aspell
	 */
	private $Translation_SpellChecker_Aspell;
	
	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {
		parent::setUp ();
		$this->Translation_SpellChecker_Aspell = new Translation_SpellChecker_Aspell();
	}
	
	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
		$this->Translation_SpellChecker_Aspell = null;

		parent::tearDown ();
	}
	
	
	/**
	 * Tests Translation_SpellChecker_Aspell->isValid()
	 */
	public function testIsValid() {
		$this->assertTrue($this->Translation_SpellChecker_Aspell->isValid('Valid text checked. But wait... Here are dots!!!'));
		$this->assertFalse($this->Translation_SpellChecker_Aspell->isValid('IntePRretation'));
	}

}

