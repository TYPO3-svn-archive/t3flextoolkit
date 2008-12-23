<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2005-2007 Mauro Lorenzutti (Webformat srl) (mauro.lorenzutti@webformat.com)
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
 * API per la conversione di un array in XML
 *
 * @author	Mauro Lorenzutti (Webformat srl) <mauro.lorenzutti@webformat.com>
 */


//require_once(PATH_tslib.'class.tslib_pibase.php');

class user_api_array2xml{
	var $extKey = 'user_api';	// The extension key.
	
	var $cObj;
	var $conf;
	
	var $errore;
	
	/**
	 * Main function
	 */
	function main($conf, $cObj)	{
		$this->conf=$conf;
		$this->cObj = $cObj;
		//$this->logged = $GLOBALS["TSFE"]->fe_user->user;
		return;
	}
	
	/**
     * Funzione per la codifica di un array in una stringa
     */
	function array2xml($data)   {
		require(PATH_typo3conf.'localconf.php');
		$config = unserialize($TYPO3_CONF_VARS['EXT']['extConf']['t3flextoolkit']);
		
		
		if ($config['mode']=='xml')	{
		       $line = "";
			   
		       foreach($data as $key => $value){
		           
		           if(is_numeric($key) && is_array($value)){
				   		$key = "content number='".$key."'";
						$key2= "content";
		           }elseif (is_numeric($key)){
				   		$key = "item number='".$key."'";
						$key2= "item";
					}else{
						$key2=$key;
					}
					if(is_array($value)){
		                $value = $this->array2xml($value);
					}
					if($value!="")	
		           		$line = $line . "<$key>" . $value . "</$key2>";
		       }
			   
		       //$line = substr($line, 1);
		       return $line;
		}	else	{
			return addslashes(serialize($data));
		}
    }
}

?>
