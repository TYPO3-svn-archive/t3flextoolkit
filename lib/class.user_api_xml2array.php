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
 * API per la conversione di una stringa XML in un array multi-dimensionale
 *
 * @author	Mauro Lorenzutti (Webformat srl) <mauro.lorenzutti@webformat.com>
 */


//require_once(PATH_tslib.'class.tslib_pibase.php');
require_once('class.user_api_xml2data_structure.php');

class user_api_xml2array {
	var $extKey = 'user_api';	// The extension key.
	
	var $cObj;
	var $conf;
	
	var $parser;
    var $node_stack = array();
	

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
    * If a string is passed in, parse it right away.
    */
    function xml2array($xmlstring="") {
		
        if ($xmlstring) {
            if (strpos($xmlstring, '<contentwfqbe>')!==false || strpos($xmlstring, '<searchwfqbe>')!==false || strpos($xmlstring, '<insertwfqbe>')!==false)	{
            	$API = t3lib_div::makeInstance(t3lib_div::makeInstanceClassName("user_api_xml2data_structure"));
	            $data_structure = $API->parse($xmlstring);
				//t3lib_div::debug($data_structure);
	            return $this->convert($data_structure);
            }	else	{
	            return unserialize(stripslashes($xmlstring));
            }
        }
        return true;
    }
    
    
    /**
     * Converte la struttura dati in un array associativo chiave->valore
     */
    function convert($struttura)    {
		//return $struttura;
        //$data = array();
		//t3lib_div::debug($struttura);
        if (sizeof($struttura["_ELEMENTS"])==0)  {
            //$data[$struttura["_NAME"]] = trim($struttura["_DATA"]);
            return trim($struttura["_DATA"]);
        }   else    {
            //$data[$struttura["_NAME"]] = array();
            foreach($struttura["_ELEMENTS"] as $key => $value)  {
                //$data[$struttura["_NAME"]][$value["_NAME"]] = $this->convert($value);
                if (($value["_NAME"]=="item" && $value["number"]!="")||($value["_NAME"]=="content" && $value["number"]!=""))
                    $data[$value["number"]] = $this->convert($value);
                else
                    $data[$value["_NAME"]] = $this->convert($value);
				
            }
			
        }
		
        return $data;
    }

}

?>
