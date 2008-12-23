<?php

if (!defined ('TYPO3_MODE')) 	die ('Access denied.');



t3lib_extMgm::addPItoST43($_EXTKEY,'pi2/class.tx_t3flextoolkit_pi2.php','_pi2',1);
t3lib_extMgm::addTypoScript($_EXTKEY,"setup",'
FLEXOBJECT = COA
FLEXOBJECT < plugin.tx_t3flextoolkit_pi2');

t3lib_extMgm::addPItoST43($_EXTKEY,'pi1/class.tx_t3flextoolkit_pi1.php','_pi1',1);
t3lib_extMgm::addTypoScript($_EXTKEY,"setup",'
FLEXAMFOBJECT = COA
FLEXAMFOBJECT < plugin.tx_t3flextoolkit_pi1

flashremoting = PAGE
flashremoting.typeNum = 1
flashremoting.config {
    disableCharsetHeader = 1
    disableAllHeaderCode = 1
    additionalHeaders = Cache-Control: no-cache, must-revalidate, max-age=0|Expires: Mon, 2 Jan 2006 01:00:00 GMT|Pragma: no-cache
    xhtml_cleaning = 0
    incT3Lib_htmlmail = 1
    debug = 0
}

flashremoting.10 < FLEXAMFOBJECT
');
	
if (TYPO3_MODE == 'BE')    {	
	
	
	
	// get default setting from extension manager
	$GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup'] = unserialize($_EXTCONF);
	// adding absolute file path for compiler files and other directives...
	// TODO: make flexFiles folders names dynamic
	$GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['absTypo3FileDocRoot']= t3lib_div::resolveBackPath(PATH_typo3.'../');
	
	$discoveryService = $GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['absTypo3FileDocRoot'] . 'fileadmin/flexfiles/services/amfphp/DiscoveryService.php';
	if (file_exists($discoveryService)) {
		//file is already there
	} else {
		t3lib_div :: writeFile($discoveryService, t3lib_div :: getUrl(t3lib_extMgm :: extPath($_EXTKEY) . 'amfphp2/services/amfphp/DiscoveryService.php'));
	}
	
		
	
	
	$TYPO3_CONF_VARS["BE"]["XCLASS"]["typo3/db_new.php"] = t3lib_extMgm::extPath($_EXTKEY).'class.ux_db_new.php';
	
	// add custom flash/flex file extensions like as/mxml/ and xml edit
	$TYPO3_CONF_VARS['SYS']['textfile_ext']=$TYPO3_CONF_VARS['SYS']['textfile_ext'].',as,mxml,xml,css';
	include_once(t3lib_extMgm::extPath($_EXTKEY).'lib/utils/class.tx_flexformsettings.php');
	
}


?>
