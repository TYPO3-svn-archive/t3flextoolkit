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
 * Module 'editor' for the 't3ftoolkit' extension.
 *
 * @author	Robbert Streng <robbert@buiswerk.info>
 */



	// DEFAULT initialization of a module [BEGIN]
unset($MCONF);
require ("conf.php");
require ($BACK_PATH."init.php");
require ($BACK_PATH."template.php");
require_once($TYPO3_MOD_PATH.'../lib/model/class.tx_t3flextoolkit_model.php');
require_once($TYPO3_MOD_PATH.'../lib/utils/templating/class.tx_t3flextoolkit_dynamicToStaticCodeParser.php');
require_once($TYPO3_MOD_PATH.'../lib/utils/treeiterator/class.tx_t3flextoolkit_project_tree_util.php');
require_once($TYPO3_MOD_PATH.'../lib/utils/file/class.tx_t3flextoolkit_class_importer.php');
require_once($TYPO3_MOD_PATH.'../lib/view/class.tx_t3flextoolkit_project_compiler_view.php');
require_once($TYPO3_MOD_PATH.'../lib/view/class.tx_t3flextoolkit_create_cairngorm_view.php');
require_once($TYPO3_MOD_PATH.'../lib/view/class.tx_t3flextoolkit_code_editor_view.php');



$LANG->includeLLFile("EXT:t3flextoolkit/mod3/locallang.xml");
require_once (PATH_t3lib."class.t3lib_scbase.php");
$BE_USER->modAccess($MCONF,1);	// This checks permissions and exits if the users has no permission for entry.
	// DEFAULT initialization of a module [END]

class tx_t3flextoolkit_module3 extends  t3lib_SCbase {
	var $pageinfo;
	var $model;
	/**
	 * Initializes the Module
	 * @return	void
	 */
	function init()	{
		global $BE_USER,$LANG,$BACK_PATH,$TCA_DESCR,$TCA,$CLIENT,$TYPO3_CONF_VARS;

		parent::init();

		/*
		if (t3lib_div::_GP("clear_all_cache"))	{
			$this->include_once[]=PATH_t3lib."class.t3lib_tcemain.php";
		}
		*/
	}

	/**
	 * Adds items to the ->MOD_MENU array. Used for the function menu selector.
	 *
	 * @return	void
	 */
	function menuConfig()	{
		global $LANG;
		$this->MOD_MENU = Array (
			"function" => Array (
				"1" => $LANG->getLL("function1"),
				"2" => $LANG->getLL("function2"),
				"3" => $LANG->getLL("function3"),
			)
		);
		parent::menuConfig();
	}

	/**
	 * Main function of the module. Write the content to $this->content
	 * If you chose "web" as main module, you will need to consider the $this->id parameter which will contain the uid-number of the page clicked in the page tree
	 *
	 * @return	[type]		...
	 */
	function main()	{
		global $BE_USER,$LANG,$BACK_PATH,$TCA_DESCR,$TCA,$CLIENT,$TYPO3_CONF_VARS;



		// Access check!
		// The page will show only if there is a valid page and if this page may be viewed by the user
		$this->pageinfo = t3lib_BEfunc::readPageAccess($this->id,$this->perms_clause);
		$access = is_array($this->pageinfo) ? 1 : 0;

		if (($this->id && $access) || ($BE_USER->user["admin"] && $this->id))	{
	
	
		
				// Draw the header.
			$this->doc = t3lib_div::makeInstance("noDoc");
			$this->doc->backPath = $BACK_PATH;
				$this->doc->form='<form  action="index.php" method="post" enctype="multipart/form-data" name="editform" >
						
						    <input type="hidden" name="id" value="'.$this->id.'">
						    
						';
						
				// JavaScript
			$this->doc->JScode = '
				<script language="javascript" type="text/javascript">
					script_ended = 0;
					function jumpToUrl(URL)	{
						document.location = URL;
					}
				</script><script  src="../../../../typo3conf/ext/t3flextoolkit/mod2/codepress/codepress.js" type="text/javascript">
			';
			$this->doc->postCode='
				<script language="javascript" type="text/javascript">
					script_ended = 1;
					if (top.fsMod) top.fsMod.recentIds["web"] = 0;
				</script>
			';
			
			
			
			
			
			
			$this->doc->loadJavascriptLib('contrib/prototype/prototype.js');
			$this->doc->loadJavascriptLib('js/common.js');
	// 		$headerSection = $this->doc->getHeader("pages",$this->pageinfo,$this->pageinfo["_thePath"])."<br />".$LANG->sL("LLL:EXT:lang/locallang_core.xml:labels.path").": ".t3lib_div::fixed_lgd_pre($this->pageinfo["_thePath"],50);

			$this->content.=$this->doc->startPage("");
	//		$this->content.=$this->doc->header($LANG->getLL("title"));
	//		$this->content.=$this->doc->spacer(5);
	//		$this->content.=$this->doc->section("",$this->doc->funcMenu($headerSection,t3lib_BEfunc::getFuncMenu($this->id,"SET[function]",$this->MOD_SETTINGS["function"],$this->MOD_MENU["function"])));
	//		$this->content.=$this->doc->divider(5);

			$this->model= tx_t3flextoolkit_model::singleton();	
			$this->model->init($this->id);
			
		
		//	t3lib_div::debug($this->model->currentFlexFormInfoAsArray);
			
			// Render content:
			$this->moduleContent();


			// ShortCut
			if ($BE_USER->mayMakeShortcut())	{
				$this->content.=$this->doc->spacer(20).$this->doc->section("",$this->doc->makeShortcutIcon("id",implode(",",array_keys($this->MOD_MENU)),$this->MCONF["name"]));
			}

			$this->content.=$this->doc->spacer(10);
		} else {
				// If no access or if ID == zero

			$this->doc = t3lib_div::makeInstance("mediumDoc");
			$this->doc->backPath = $BACK_PATH;

			$this->content.=$this->doc->startPage("");
			$this->content.=$this->doc->header("");
			
		}
				
	
	}

	/**
	 * Prints out the module HTML
	 *
	 * @return	void
	 */
	function printContent()	{

		$this->content.=$this->doc->endPage();
		echo $this->content;
		
//t3lib_BEfunc::getSetUpdateSignal('updatePageTree');
	}

	/**
	 * Generates the module content
	 *
	 * @return	void
	 */
	function moduleContent()	{
		
				
				$cairngormCrView = t3lib_div::makeInstance('tx_t3flextoolkit_create_cairngorm_view');
				$cairngormCrView->init($this->model);
				$content  .= $cairngormCrView->getCurrentView();				
				
				$this->content.=$this->doc->section("",$content,0,1);
		
			
	
	}
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/t3flextoolkit/mod3/index.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/t3flextoolkit/mod3/index.php']);
}




// Make instance:
$SOBE = t3lib_div::makeInstance('tx_t3flextoolkit_module3');
$SOBE->init();

// Include files?
foreach($SOBE->include_once as $INC_FILE)	include_once($INC_FILE);

$SOBE->main();
$SOBE->printContent();
	
?>