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
/**
 * Addition of an item to the clickmenu
 *
 * @author	 
 */


class tx_t3flextoolkit_cm1 {
	 function main(&$backRef,$menuItems,$table,$uid)    {
        global $BE_USER,$TCA,$LANG;
    
        $localItems = Array();
        if (!$backRef->cmLevel)    {
            
                // Returns directly, because the clicked item was not from the pages table 
$doktypeid=t3lib_befunc::getRecord($table,$uid);          

 if ($table=="pages" AND $doktypeid['doktype']==$GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['registertodoktypesMXML']) {
         
            $url = t3lib_extMgm::extRelPath("t3flextoolkit")."mod2/index.php?id=".$uid;
				$cmsStyleHook='';				
				$localItems[] = $backRef->linkItem(
					'edit mxml='.$uid,
					$backRef->excludeIcon('<div style="'.$cmsStyleHook.'"><img src="http://'.t3lib_div::getThisUrl().'../typo3conf/ext/t3flextoolkit/icons/C_ActionScriptFile_edit_md.png" width="16" height="16" border="0" align="top" /></div>'),
					$backRef->urlRefForCM($url),
					1	// Disables the item in the top-bar. Set this to zero if you with the item to appear in the top bar!
				);  
$menuItems = array_merge($localItems,$menuItems);    
 return $menuItems;
}

 if ($table=="pages" AND $doktypeid['doktype']==$GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['registertodoktypesACTIONSCRIPT']) {
         
            $url = t3lib_extMgm::extRelPath("t3flextoolkit")."mod2/index.php?id=".$uid;
								
				$localItems[] = $backRef->linkItem(
					'edit mxml='.$uid,
					$backRef->excludeIcon('<div style="'.$cmsStyleHook.'"><img src="http://'.t3lib_div::getThisUrl().'../typo3conf/ext/t3flextoolkit/icons/C_ActionScriptFile_edit_md.png" width="16" height="16" border="0" align="top" /></div>'),
					$backRef->urlRefForCM($url),
					1	// Disables the item in the top-bar. Set this to zero if you with the item to appear in the top bar!
				);  
$menuItems = array_merge($localItems,$menuItems);    
 return $menuItems;
}

 if ($table=="pages" AND $doktypeid['doktype']==$GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['registertodoktypesPROJECT']) {
            
                // Adds the regular item:
            $LL = $this->includeLL();
            
                // Repeat this (below) for as many items you want to add!
                // Remember to add entries in the localconf.php file for additional titles.
           



          $url= '../typo3/alt_doc.php?edit[pages]['.$uid.']=edit';
				
				$localItems[] = $backRef->linkItem(
					'Edit properties',
					$backRef->excludeIcon('<div style="'.$cmsStyleHook.'"><img src="http://'.t3lib_div::getThisUrl().'../typo3conf/ext/t3flextoolkit/icons/moduleicon_edit.gif" width="18" height="16" border="0" align="top" /></div>'),
					$backRef->urlRefForCM($url),
					1	// Disables the item in the top-bar. Set this to zero if you with the item to appear in the top bar!
				);
				$treeDat = t3lib_div::xml2array($doktypeid['tx_flextoolkit_ds']); 
				
			            $url = t3lib_extMgm::extRelPath("t3flextoolkit")."mod1/index.php?id=".$uid;
								
				$localItems[] = $backRef->linkItem(
					'Compiler for uid='.$uid,
					$backRef->excludeIcon('<div style="'.$cmsStyleHook.'"><img src="http://'.t3lib_div::getThisUrl().'../typo3conf/ext/t3flextoolkit/icons/build.gif" width="16" height="16" border="0" align="top" /></div>'),
					$backRef->urlRefForCM($url),
					1	// Disables the item in the top-bar. Set this to zero if you with the item to appear in the top bar!
				);
         // Simply merges the two arrays together and returns ...
         //   $menuItems=array_merge($menuItems,$localItems);
    $menuItems = array_merge($localItems,$menuItems);    
 return $menuItems;
}
	
}
   		
        return $menuItems;
    }
    
    /**
     * Reads the [extDir]/locallang.xml and returns the $LOCAL_LANG array found in that file.
     *
     * @return    [type]        ...
     */
    function includeLL()    {
        global $LANG;
    
        $LOCAL_LANG = $LANG->includeLLFile('EXT:t3flextoolkit/locallang.xml',FALSE);
        return $LOCAL_LANG;
    }
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/t3flextoolkit/class.tx_t3flextoolkit_cm1.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/t3flextoolkit/class.tx_t3flextoolkit_cm1.php']);
}

?>