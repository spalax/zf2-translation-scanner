<?php
namespace Zf2TranslationScanner;

class System {

	/**
	 * Copy file in system
	 * @param string $from
	 * @param string $to
	 * @throws \RuntimeException
	 */
	public function copy($from, $to)
	{
		$command = "cp -f $from $to";

		if (system($command) < 0) {
			throw new \RuntimeException("Cannot copy files from $from to $to");
		}
	}

	/**
	 * Move files in filesystem
	 * @param string $from
	 * @param string $to
	 * @throws \RuntimeException
	 */
	public function move($from, $to)
	{
		if (system("mv ".$from." ".$to) < 0) {
			throw new \RuntimeException("Cannot move files from ".$from." to $to");
		}
	}

	/**
	 * Delete files on filesystem
	 * @param string $fileName
	 * @throws \RuntimeException
	 */
	public function remove($fileName)
	{
		if(file_exists($fileName)){
			if (system("rm -rf ".$fileName) < 0) {
				throw new \RuntimeException("Cannot remove ".$fileName);
			}
		}
	}

	/**
	 * Create Portable Object Template file based on given source files
	 * @param string $sourceDir Directory to take source files from
	 * @param string $bugsAdress Email adress to send bug reports
	 * @throws \RuntimeException
	 */
	public function generatePotFile($sourceDir, $bugsAdress)
	{
		system('>'.$sourceDir.'/messages.po');
        
		if ((system("xgettext --force-po -o $sourceDir/messages.po --from-code=utf-8 --join-existing --keyword=_ --language=PHP --copyright-holder='Your company' --msgid-bugs-address=".$bugsAdress." `find ".$sourceDir." -type f`", $code)) && $code != 0) {
			throw new \RuntimeException("Xgettext command cannot be done exit with code ($code)");
		}


        $this->move($sourceDir.'/messages.po', $sourceDir.'/messages.pot');
        return $sourceDir.'/messages.pot';
	}

	/**
	 * Create new I18n file based on PO Template for given locale
	 * @param string $potFile
	 * @param string $poFile
	 * @param string $locale
	 * @throws \RuntimeException
	 */
	public function generatePoFile($potFile, $poFile, $locale)
	{
		if (($code = system("msginit -i ".$potFile." -o ".$poFile." -l ".$locale." --no-translator ")) < 0) {
			throw new \RuntimeException("Msginit command cannot be done exit with code ($code)");
		}
	}

	/**
	 * Update I18n PO File by Template file.
	 * Adds new words to list of translated words
	 * Comments out words that dont't exist in project anymore
	 * @param string $poFile
	 * @param string $potFile
	 * @throws \RuntimeException
	 */
	public function updatePoFile($poFile, $potFile)
	{
		if (($code = system("msgmerge -U -N ".$poFile." ".$potFile)) < 0) {
			throw new \RuntimeException("Msgmerge command cannot be done exit with code ($code)");
		}
	}

	/**
	 * Create binary translation files based on Portable Object I18n files
	 * @param string $poName
	 * @param string $moName
	 * @throws \RuntimeException
	 */
	public function generateMoFileFromPo($poName, $moName)
	{
		if (($code = system("msgfmt {$poName} -o {$moName}")) < 0) {
			throw new \RuntimeException("Msgfmt command cannot be done exit with code ($code)");
		}
	}

}
