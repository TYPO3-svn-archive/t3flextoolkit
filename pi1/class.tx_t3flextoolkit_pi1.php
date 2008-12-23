<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2008 Robbert Streng <robbert@buiswerk.info>
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
 * @author	Robbert Streng <robbert@buiswerk.info>
 * @package	TYPO3
 * @subpackage	tx_t3flextoolkit
 */
class tx_t3flextoolkit_pi1 extends tslib_pibase {
	var $prefixId      = 'tx_t3flextoolkit_pi1';		// Same as class name
	var $scriptRelPath = 'pi1/class.tx_t3flextoolkit_pi1.php';	// Path to this script relative to the extension dir.
	var $extKey        = 't3flextoolkit';	// The extension key.
	var $pi_checkCHash = true;

	/**
	 * The main method of the PlugIn
	 *
	 * @param	string	$content: The PlugIn content
	 * @param	array		$conf: The PlugIn configuration
	 * @return	This plugin returns an AMF3 services in the amfphp2 folder of this extension
	 */
	function main($content,$conf)	{
		if (t3lib_div::GPvar('mode')=='browser') {
			return $this->serviceBrowser($content,$conf);
		} else {
		$GLOBALS['TSFE']->TYPO3_CONF_VARS['FE']['debug'] = 0;
		@ini_set('include_path', '.:'.t3lib_extMgm::extPath('t3flextoolkit')."amfphp2/");
		include(t3lib_extMgm::extPath('t3flextoolkit')."amfphp2/gateway.php");
		}
	}
	
	/**
	 *Service Browser Preview of the PlugIn
	 *
	 * @param	string	$content: The PlugIn content
	 * @param	array		$conf: The PlugIn configuration
	 * @return	Service Browser Preview
	 */
	function serviceBrowser($content,$conf)	{
				
				

$swfobjectJS= t3lib_div::getIndpEnv('TYPO3_SITE_URL')."typo3conf/ext/t3flextoolkit/mod1/swfobject/swfobject.js";
$swfFileName=  t3lib_div::getIndpEnv('TYPO3_SITE_URL')."typo3conf/ext/t3flextoolkit/amfphp2/browser/servicebrowser.swf";
$flashVarFromUrl="&vars=true&baseUrl=".t3lib_div::getIndpEnv('TYPO3_SITE_URL')."&id=".t3lib_div::GPvar('id')."&type=1";


$serviceBrowser = <<<HEREDOC
						<!DOCTYPE html
	PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<!-- 
	This website is powered by TYPO3 - inspiring people to share!
	TYPO3 is a free open source Content Management Framework initially created by Kasper Skaarhoj and licensed under GNU/GPL.
	TYPO3 is copyright 1998-2008 of Kasper Skaarhoj. Extensions are copyright of their respective owners.
	Information and contribution at http://typo3.com/ and http://typo3.org/
-->

	<title>flexApplicationTest</title>
	<meta name="generator" content="TYPO3 4.2 CMS" />
		<style>
				body { margin: 0px; overflow:hidden }
		</style>
		
		<script type="text/javascript" 
								src="$swfobjectJS">
							</script>
</head>
<body>




	<!--

		BEGIN: Content of extension "t3flextoolkit", plugin "tx_t3flextoolkit_pi1 serviceBrowser"

	-->
	<div class="tx-t3flextoolkit-pi1">
						
							<div  id="flexAmfBrowser">		
								[no flash]
							</div>

							<script type="text/javascript">
								// <![CDATA[

								var so = new SWFObject("$swfFileName", "servicebrowser", "100%","100%", "9", "#FFFFFF");
								so.addParam("FlashVars","$flashVarFromUrl");
								so.write("flexAmfBrowser");	

								// ]]>
							</script>
							
							</div>
	
	<!-- END: Content of extension "t3flextoolkit", plugin "tx_t3flextoolkit_pi1 serviceBrowser" -->

	
</body>
</html>
HEREDOC;

								global $BE_USER,$LANG,$BACK_PATH,$TCA_DESCR,$TCA,$CLIENT,$TYPO3_CONF_VARS;

					$this->pageinfo = t3lib_BEfunc::readPageAccess($this->id,$this->perms_clause);
					$access = is_array($this->pageinfo) ? 1 : 0;

					if (($this->id && $access) || ($BE_USER->user['admin'] && !$this->id))	{
	
				return $serviceBrowser;		
				} else {
					return "no access...";
				}
		}	

}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/t3flextoolkit/pi1/class.tx_t3flextoolkit_pi1.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/t3flextoolkit/pi1/class.tx_t3flextoolkit_pi1.php']);
}

?>