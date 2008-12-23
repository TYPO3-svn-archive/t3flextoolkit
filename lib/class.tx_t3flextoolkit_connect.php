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
 * DB connection class
 *
 * @author	Mauro Lorenzutti <mauro.lorenzutti@webformat.com>
 */

class tx_t3flextoolkit_connect {
	var $extKey = 't3flextoolkit'; // The extension key.

	function connect($where) {
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*', 'tx_t3flextoolkit_query', $where . 'tx_t3flextoolkit_query.hidden!=1 AND tx_t3flextoolkit_query.deleted!=1');
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			if ($row['credentials'] == 0) {
				// Local TYPO3 DB
				$h = NewADOConnection("mysql"); // TODO: correct this for DBAL compatibility
				$resultConnection = $h->Connect(TYPO3_db_host, TYPO3_db_username, TYPO3_db_password, TYPO3_db);
				if ($resultConnection) {
					global $TYPO3_CONF_VARS;
					if ($TYPO3_CONF_VARS['SYS']['setDBinit']!='')
						$h->query($TYPO3_CONF_VARS['SYS']['setDBinit']);
					return array("conn" => $h, "row" => $row);
				} else {
					return false;
				}
			} else {
				$res2 = $GLOBALS['TYPO3_DB']->exec_SELECTquery('host,dbms,username,passw,conn_type', 'tx_t3flextoolkit_credentials', 'tx_t3flextoolkit_credentials.uid=' . $row['credentials'], '', '', '');
				while ($row2 = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res2)) {
					$h = NewADOConnection($row2['dbms']); //starts a new connection 
					
						if ($row2['conn_type']=="PConnect")
							$resultConnection = $h->PConnect($row2['host'], $row2['username'], $row2['passw'], $row['dbname']);
						elseif ($row2['conn_type']=="NConnect")
							$resultConnection = $h->NConnect($row2['host'], $row2['username'], $row2['passw'], $row['dbname']);
						else
							$resultConnection = $h->Connect($row2['host'], $row2['username'], $row2['passw'], $row['dbname']);
					}
					if ($resultConnection) {
						return array("conn" => $h, "row" => $row);
					} else {
						return false;
					}
				
			}
		}
		return false;
	}

}
?>