<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2008 Robbert Streng <Robbert@buiswerk.info>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

require_once(PATH_tslib."class.tslib_pibase.php");

/**
 * 
 *
 * @author	Robbert Streng 
 * @package	TYPO3
 * @subpackage	tx_t3flextoolkit
 */
class tx_t3flextoolkit_pi2 extends tslib_pibase {
	var $prefixId      = 'tx_t3flextoolkit_pi2';		// Same as class name
	var $scriptRelPath = 'tx_t3flextoolkit_pi2.php';	// Path to this script relative to the extension dir.
	var $extKey        = 't3flextoolkit';	// The extension key.
	var $pi_checkCHash = true;

	/**
	 * The main method of the PlugIn
	 *
	 * @param	string		$content: The PlugIn content
	 * @param	array		$conf: The PlugIn configuration
	 * @return	The content that is displayed on the website
	 */
	function main($content,$conf)	{
		$this->conf=$conf;
		$this->pi_setPiVarDefaults();
		$this->pi_loadLL();
		$this->pi_USER_INT_obj=1;	// Configuring so caching is not expected. This value means that no cHash params are ever set. We do this, because it's a USER_INT object!

if ($this->conf['flashVars'.'.']['debug']=='true') {
t3lib_div::debug($this->conf['flashVars'.'.']);
}
if ($this->conf['debug']=='this') {
t3lib_div::debug($this);
}

############################################################
#   compiler settings   [leave blank for extension config defaults]	  
############################################################

$generateCompilerQueItem =   $this->conf['generateCompilerQueItem'];
$autoGenerateSwf =   $this->conf['autoGenerateSwf'];

$compilerQueItemId = $this->conf['compilerQueItemId'];
$fullMxmlcCompilerLocation = $this->conf['fullMxmlcCompilerLocation'];
$flexFilesPath = $this->conf['flexFilesPath'];
$baseUrl =$this->conf['$baseUrl'];

$extraCompilerOptions=$this->conf['extraCompilerOptions'];
$antbasedir = $this->conf['antbasedir'];
$outputfolder=$this->conf['outputfolder'];

$swfFileName=$outputfolder.$this->conf['swfFileName'];
$sourceFolder=$this->conf['sourceFolder'];
$target = $this->conf['target'];


$swfCacheTimeOut=$this->conf['swfCacheTimeOut'];

$autoFlexSettingsFromFLEXAPPLICATIONid = $this->conf['autoFlexSettingsFromFLEXAPPLICATIONid'];

############################################################
#    movie parameters  
############################################################

# movie properties
$width=$this->conf['width'];
$height=$this->conf['height'];
$frameRate=$this->conf['frameRate'];
$backgroundcolor=$this->conf['backgroundColor'];
$serviceconfigId = $this->conf['serviceconfigId'];
$crossdomain	= $this->conf['serviceconfigId'];
$crossdomainPid     =  $this->conf['crossdomainPid'];
$crossdomainType     =   $this->conf['crossdomainType'];
$abssiteroot 	     =    $this->conf['abssiteroot'];
$amfServiceUid	     =    $this->conf['amfServiceUid'];
$imageuploadFolder  =   $this->conf['imageuploadFolder'];
$txuploads 	     =    $this->conf['txuploads'];
############################################################
#  swfObject parameters 
# [see http://blog.deconcept.com/swfobject/]
# [leave blank for random values]	   
############################################################
for($i=0; $i<7; $i++){
$random_string .= chr(rand(0,25)+65);
}

if ($this->conf['swfObjecInstanceName']=="") {
$swfObjecInstanceNameValue=so.t3lib_div::md5int($random_string);
} else {
$swfObjecInstanceNameValue=$this->conf['swfObjecInstanceName'];
}
$uniqueSwfNameValue=$swfObjecInstanceNameValue.'movieId';

if ($this->conf['swfFlashContentDivID']=="") {
$uniqueValueFlashContent=$swfObjecInstanceNameValue.'_swfFlashContentDivID';
} else {
$uniqueValueFlashContent=$this->conf['swfFlashContentDivID'];

}

$swfObjecInstanceName = $this->conf['swfObjecInstanceName'];
$swfFlashContentDivStyle = $this->conf['swfFlashContentDivStyle'];
$swfPreventBrowserAndFlashPlayerCache = $this->conf['swfPreventBrowserAndFlashPlayerCache'];

$swfJavascriptObjecName=$this->conf['swfJavascriptObjecName'];
$backgroundcolor=$this->conf['backgroundColor'];


if ($this->conf['flashVars'.'.']) {
$flashVarFromUrl=t3lib_div::implodeArrayForUrl('', t3lib_div::_GET(),'',0,0);
$implodedUrl = '';
$implodedUrl = t3lib_div::implodeArrayForUrl('',$this->conf['flashVars'.'.'],'',0,0);
$flashVarFromUrl= $implodedUrl.$flashVarFromUrl;
}
if ($this->conf['swfFlashContentDivAltenatieveText']=="") {
$swfFlashContentDivAltenatieveText = '<strong>You need to upgrade your Flash Player</strong>
			This is replaced by the Flash content. 
			Place your alternate content here and users without the Flash plugin or with 
			Javascript turned off will see this. Content here allows you to leave out <code>noscript</code> 
			tags. Include a link to <a href="fullpage.html?detectflash=false">bypass the detection</a> if you wish.	
	';

} else {

$swfFlashContentDivAltenatieveText=$this->conf['swfFlashContentDivAltenatieveText'];

}


if ($generateCompilerQueItem=="true") {
	//addToCompilerTasks();
} else {
	// just show the swf...
}





$swfobjectJS= t3lib_div::getIndpEnv('TYPO3_SITE_URL')."typo3conf/ext/t3flextoolkit/mod1/swfobject/swfobject.js";

$swfFilename=$this->conf['swfLocation'];
$swfObjOutput = <<<HEREDOC
						
							<script type="text/javascript" 
								src="$swfobjectJS">
							</script>
							<div style="padding:0;" id="flashcontent">		
								<strong>You need to upgrade your Flash Player</strong>
								This is replaced by the Flash content. 
								Place your alternate content here and users without the Flash plugin or with 
								Javascript turned off will see this. Content here allows you to leave out <code>noscript</code> 
								tags. Include a link to <a href="fullpage.html?detectflash=false">bypass the detection</a> if you wish.	
							</div>

			<script type="text/javascript">
		// <![CDATA[
		var so$uid = new SWFObject("$swfFileName", "flashelement", "$width","$height", "9", "$backgroundcolor");
		so$uid.addParam("scale", "noscale");
		so$uid.addParam("salign","C");
		so$uid.addParam("FlashVars","$flashVarFromUrl");
		so$uid.addParam ("wmode","transparent");
		so$uid.addParam("allowscriptaccess","always");
		so$uid.addParam("allownetworking","external");
		so$uid.write("flashcontent");	
		
		// ]]>
		</script>
HEREDOC;
	
	return $this->pi_wrapInBaseClass($content.$swfObjOutput);




	}

}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/t3flextoolkit/pi2/class.tx_t3flextoolkit_pi2.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/t3flextoolkit/pi2/class.tx_t3flextoolkit_pi2.php']);
}

?>