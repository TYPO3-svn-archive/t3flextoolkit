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
 * Module 'editor' for the 't3flextoolkit' extension.
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


$LANG->includeLLFile("EXT:t3flextoolkit/mod2/locallang.xml");
require_once (PATH_t3lib."class.t3lib_scbase.php");
$BE_USER->modAccess($MCONF,1);	// This checks permissions and exits if the users has no permission for entry.
	// DEFAULT initialization of a module [END]

class tx_t3flextoolkit_module2 extends  t3lib_SCbase {
	var $pageinfo;
	var $model;
	/**
	 * Initializes the Module
	 * @return	void
	 */
	function init()	{
		global $BE_USER,$LANG,$TYPO3_MOD_PATH,$BACK_PATH,$TCA_DESCR,$TCA,$CLIENT,$TYPO3_CONF_VARS;

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
	
	
			$this->model= tx_t3flextoolkit_model::singleton();	
			$this->model->init($this->id);
	
			
			$this->doc = t3lib_div::makeInstance('template');
			$this->doc->docType='xhtml_trans';
			$this->doc->backPath = $BACK_PATH;
			



	//$GLOBALS['TBE_STYLES']['htmlTemplates']['templates/editor.html'] = '../'.t3lib_extMgm::extRelPath('t3flextoolkit').'lib/templates/editor.html');
	//$this->doc->setModuleTemplate('templates/editor.html');
			

$this->doc->setModuleTemplate('');
$this->doc->moduleTemplate = t3lib_div::getURL(t3lib_extMgm::extPath('t3flextoolkit') . 'lib/templates/editor.html');




			//$this->doc->styleSheetFile='';
			//	$this->doc->styleSheetFile2='';

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
							</script>
						';
					$this->doc->postCode='
							<script language="javascript" type="text/javascript"> 
								script_ended = 1;
								if (top.fsMod) top.fsMod.recentIds["web"] = 0;
							</script>
						';	
			/*				switch ($this->model->codeType) {
						case "actionscript":
						$this->doc->postCode .='<script type="text/javascript">
			  	  	var editor = CodeMirror.fromTextArea("code", {
			 			    parserfile: ["tokenizejavascript.js", "parsejavascript.js"],
			    stylesheet: "../lib/codemirror/css/jscolors.css",
			    path: "../lib/codemirror/js/",
			    continuousScanning: 500, width: "95%", height:"550px",
				 autoMatchParens: true
					 });
			</script>';
					break;
				case "mxml":
						$this->doc->postCode .='<script type="text/javascript">
  var editor = CodeMirror.fromTextArea("code", {   
    parserfile: "parsexml.js",
    stylesheet: "../lib/codemirror/css/xmlcolors.css",
    path: "../lib/codemirror/js/", width: "95%", height:"550px",
    continuousScanning: 500, autoMatchParens: true
  });
</script>';
					break;
			}
*/
			$docHeaderButtons = $this->getButtons($this->MOD_SETTINGS['function']==0 ? 'quickEdit' : '');
			$markers = array(
				'CSH' => $docHeaderButtons['csh'],
				'TOP_FUNCTION_MENU' => $this->editSelect . $this->topFuncMenu
			);	

			
			$this->doc->loadJavascriptLib('contrib/prototype/prototype.js');
			$this->doc->loadJavascriptLib('js/common.js');
			$this->doc->loadJavascriptLib('../typo3conf/ext/t3flextoolkit/lib/codemirror/js/codemirror.js');
			$this->doc->loadJavascriptLib('../typo3conf/ext/t3flextoolkit/lib/codemirror/js/mirrorframe.js');
			$this->content = $this->doc->startPage($LANG->getLL('title'));
		
	if ($this->model->codeType=='noflex' ||  $this->model->codeType=='project') {
		
		
		$this->content.="select a actionscript or mxml node";
				} else {
				$this->content.= $this->doc->moduleBody($this->pageinfo, $docHeaderButtons, $markers);	
				$this->moduleContent();
				}
				
			// ShortCut
			if ($BE_USER->mayMakeShortcut())	{
				$this->content.=$this->doc->spacer(20).$this->doc->section("",$this->doc->makeShortcutIcon("id",implode(",",array_keys($this->MOD_MENU)),$this->MCONF["name"]));
			}

			$this->content.=$this->doc->spacer(10);
		} else {
				// If no access or if ID == zero

			$this->doc = t3lib_div::makeInstance("mediumDoc");
			$this->doc->backPath = $BACK_PATH;

			$this->content.=$this->doc->startPage($LANG->getLL("title"));
			$this->content.=$this->doc->header($LANG->getLL("title"));
			$this->content.=$this->doc->spacer(5);
			$this->content.=$this->doc->spacer(10);
		}
	}

	/**
	 * Prints out the module HTML
	 *
	 * @return	void
	 */
	function printContent()	{

		if( t3lib_div::_GP('refreshtree')=='true') {									
				t3lib_BEfunc::getSetUpdateSignal('updatePageTree');	
		}

		$this->content.=$this->doc->endPage();
		echo $this->content;
	}

	/**
	 * Generates the module content
	 *
	 * @return	void
	 */
	function moduleContent()	{
		switch((string)$this->MOD_SETTINGS["function"])	{
			case 1:
				//$compilerView = t3lib_div::makeInstance('tx_t3flextoolkit_project_compiler_view');
				//$compilerView->init($this->model);
				//$content  = $compilerView->getCurrentView();
				$content .=  tx_t3flextoolkit_code_editor_view::singleton()->runEditor();		
				
			$this->content.=$this->doc->section("",$content,0,1);
			break;
		}
	}
	
		/**
	 * Create the panel of buttons for submitting the form or otherwise perform operations.
	 *
	 * @param	string	Identifier for function of module
	 * @return	array	all available buttons as an assoc. array
	 */
	private function getButtons($function = '')	{
		global $TCA, $LANG, $BACK_PATH, $BE_USER;
		
		$buttons = array(
			'view' => '',
			'history_page' => '', 
			'new_content' => '', 
			'move_page' => '',
			'move_record' => '',
			'new_page' => '', 
			'edit_page' => '',
			'record_list' => '',
			'csh' => '',
			'shortcut' => '',
			'cache' => '',
			'savedok' => '',
			'savedokshow' => '',
			'closedok' => '',
			'deletedok' => '',
			'undo' => '',
			'history_record' => ''
		);

			// View page
//		$buttons['view'] = '<a href="#" onclick="' . htmlspecialchars(t3lib_BEfunc::viewOnClick($this->pageinfo['uid'], $BACK_PATH, t3lib_BEfunc::BEgetRootLine($this->pageinfo['uid']))) . '">' .
//				'<img' . t3lib_iconWorks::skinImg($BACK_PATH, 'gfx/zoom.gif', 'width="12" height="12"') . ' title="' . $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_core.php:labels.showPage', 1) . '" hspace="3" alt="" />' .
//				'</a>';

			// Shortcut
		if ($BE_USER->mayMakeShortcut())	{
			$buttons['shortcut'] = $this->doc->makeShortcutIcon('id, edit_record, pointer, new_unique_uid, search_field, search_levels, showLimit', implode(',', array_keys($this->MOD_MENU)), $this->MCONF['name']);
		}
		
			// Cache
		if (!$this->modTSconfig['properties']['disableAdvanced'])	{
			$buttons['cache'] = '<a href="' . htmlspecialchars('db_layout.php?id=' . $this->pageinfo['uid'] . '&clear_cache=1') . '">' .
					'<img' . t3lib_iconWorks::skinImg($BACK_PATH, 'gfx/clear_cache.gif', 'width="14" height="14"') . ' title="' . $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_core.php:labels.clear_cache', 1) . '" alt="" />' .
					'</a>';
		}
		
			// If access to Web>List for user, then link to that module.
		if ($BE_USER->check('modules','web_list'))	{
			$href = $BACK_PATH . 'db_list.php?id=' . $this->pageinfo['uid'] . '&returnUrl=' . rawurlencode(t3lib_div::getIndpEnv('REQUEST_URI'));
			$buttons['record_list'] = '<a href="' . htmlspecialchars($href) . '">' .
					'<img' . t3lib_iconWorks::skinImg($BACK_PATH, 'gfx/list.gif', 'width="11" height="11"') . ' title="' . $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_core.php:labels.showList', 1) . '" alt="" />' .
					'</a>';
		}
			
		if (!$this->modTSconfig['properties']['disableIconToolbar'])	{		
			
				// Page history
			$buttons['history_page'] = '<a href="#" onclick="' . htmlspecialchars('jumpToUrl(\'' . $BACK_PATH . 'show_rechis.php?element=' . rawurlencode('pages:' . $this->id) . '&returnUrl=' . rawurlencode(t3lib_div::getIndpEnv('REQUEST_URI')) . '#latest\');return false;') . '">' .
						'<img' . t3lib_iconWorks::skinImg($BACK_PATH, 'gfx/history2.gif', 'width="13" height="12"') . ' vspace="2" hspace="2" align="top" title="' . $LANG->getLL('recordHistory', 1) . '" alt="" />' .
						'</a>  <input  type="checkbox" name="svastemplate" /><input  type="hidden" value="'.$this->model->currentPageInfo['title'].'" type="input" name="classNameChange" />';
				// New content element
				$buttons['new_content']='';
				
			
					if (t3lib_div::GPvar('staticCode')=='false' || t3lib_div::GPvar('staticCode')=='') {
				$buttons['new_content'] .='		
					<input class="c-inputButton" type="image" onclick="document.editform.staticCode.value='."'true'".';document.editform._save.submit();"
					name="savbutton"  class="c-inputButton" src="../../../../typo3/sysext/t3skin/icons/gfx/zoom.gif" width="16" height="16" title="Show Static" ></input>
						';	
						
			$buttons['new_content'] .='<input type="hidden"  name="staticCode"/>
		<input class="c-inputButton" type="image" onclick="document.editform.staticCode.value='."'false'".';document.editform._save.submit();" 
					name="save"  class="c-inputButton" src="../../../../typo3/sysext/t3skin/icons/gfx/savedok.gif" width="16" height="16" title="Save document" ></input>';
				} else {
					
						$buttons['new_content'] .='		
					<input class="c-inputButton" type="image" onclick="document.editform.staticCode.value='."'true'".';document.editform._save.submit();"
					name="savbutton"  class="c-inputButton" src="../../../../typo3/sysext/t3skin/icons/gfx/refresh_n.gif" width="16" height="16" title="refresh" ></input>
						';	
					}
				// Move page
			$buttons['move_page'] = '<a href="' . htmlspecialchars($BACK_PATH . 'move_el.php?table=pages&uid=' . $this->id . '&returnUrl=' . rawurlencode(t3lib_div::getIndpEnv('REQUEST_URI'))) . '">' .
						'<img' . t3lib_iconWorks::skinImg($BACK_PATH, 'gfx/move_page.gif', 'width="11" height="12"') . ' vspace="2" hspace="2" align="top" title="' . $LANG->getLL('move_page', 1) . '" alt="" />' .
						'</a>';
				// Move record
			if (t3lib_div::testInt($this->eRParts[1])) {
				$buttons['move_record'] = '<a href="' . htmlspecialchars($BACK_PATH . 'move_el.php?table=' . $this->eRParts[0] . '&uid=' . $this->eRParts[1] . '&returnUrl=' . rawurlencode(t3lib_div::getIndpEnv('REQUEST_URI'))) . '">' .
						'<img' . t3lib_iconWorks::skinImg($BACK_PATH, 'gfx/move_' . ($this->eRParts[0] == 'tt_content' ? 'record' : 'page') . '.gif', 'width="11" height="12"') . ' class="c-inputButton" title="' . $LANG->getLL('move_' . ($this->eRParts[0] == 'tt_content' ? 'record' : 'page'), 1) . '" alt="" />' .
						'</a>';
			}
				// Create new page (wizard)
			$buttons['new_page'] = '<a href="#" onclick="' . htmlspecialchars('jumpToUrl(\'' . $BACK_PATH . 'db_new.php?id=' . $this->id . '&pagesOnly=1&returnUrl=' . rawurlencode(t3lib_div::getIndpEnv('REQUEST_URI')) . '\');return false;') . '">' .
						'<img' . t3lib_iconWorks::skinImg($BACK_PATH, 'gfx/new_page.gif', 'width="13" height="12"') . ' hspace="0" vspace="2" align="top" title="' . $LANG->getLL('newPage', 1) . '" alt="" />' .
						'</a>';
				// Edit page properties
			if ($this->CALC_PERMS&2)	{
				$params='&edit[pages][' . $this->id . ']=edit';
				$buttons['edit_page'] = '<a href="#" onclick="' . htmlspecialchars(t3lib_BEfunc::editOnClick($params, $BACK_PATH)) . '">' .
							'<img' . t3lib_iconWorks::skinImg($BACK_PATH, 'gfx/edit2.gif', 'width="11" height="12"') . ' hspace="2" vspace="2" align="top" title="' . $LANG->getLL('editPageProperties', 1) . '" alt="" />' .
							'</a>';
			}

				// Add CSH (Context Sensitive Help) icon to tool bar
			if($function == 'quickEdit') {
				$buttons['csh'] = t3lib_BEfunc::cshItem($this->descrTable, 'quickEdit', $BACK_PATH, '', FALSE, 'margin-top: 0px; margin-bottom: 0px;');
			} else {
				$buttons['csh'] = t3lib_BEfunc::cshItem($this->descrTable, 'columns_' . $this->MOD_SETTINGS['function'], $BACK_PATH, '', FALSE, 'margin-top: 0px; margin-bottom: 0px;');
			}
			
			if($function == 'quickEdit') {
					// Save record
				$buttons['savedok'] = '<input class="c-inputButton" type="image" name="savedok"' . t3lib_iconWorks::skinImg($BACK_PATH, 'gfx/savedok.gif','') . ' title="' . $LANG->sL('LLL:EXT:lang/locallang_core.php:rm.saveDoc', 1) . '" alt="" />';
	
					// Save record and show page
				$buttons['savedokshow'] = '<a href="#" onclick="' . htmlspecialchars('document.editform.redirect.value+=\'&popView=1\'; TBE_EDITOR.checkAndDoSubmit(1); return false;') . '">' .
					'<img' . t3lib_iconWorks::skinImg($BACK_PATH, 'gfx/savedokshow.gif', 'width="21" height="16"') . ' class="c-inputButton" title="' . $LANG->sL('LLL:EXT:lang/locallang_core.php:rm.saveDocShow', 1) . '" alt="" />' .
					'</a>';
	
					// Close record
				$buttons['closedok'] = '<a href="#" onclick="' . htmlspecialchars('jumpToUrl(unescape(\'' . rawurlencode($this->closeUrl) . '\')); return false;') . '">' .
					'<img' . t3lib_iconWorks::skinImg($BACK_PATH, 'gfx/closedok.gif', 'width="21" height="16"') . ' class="c-inputButton" title="' . $LANG->sL('LLL:EXT:lang/locallang_core.php:rm.closeDoc', 1) . '" alt="" />' .
					'</a>';
	
					// Delete record
				if($this->deleteButton) {
					$buttons['deletedok'] = '<a href="#" onclick="' . htmlspecialchars('return deleteRecord(\'' . $this->eRParts[0] . '\',\'' . $this->eRParts[1] . '\',\'' . t3lib_div::getIndpEnv('SCRIPT_NAME') . '?id=' . $this->id . '\');') . '">' .
						'<img' . t3lib_iconWorks::skinImg($BACK_PATH, 'gfx/deletedok.gif','width="21" height="16"') . ' class="c-inputButton" title="' . $LANG->getLL('deleteItem', 1) . '" alt="" />' .
						'</a>';
				}

					
				if($this->undoButton) {		
						// Undo button
					$buttons['undo'] = '<a href="#" onclick="' . htmlspecialchars('window.location.href=\'' . $BACK_PATH . 'show_rechis.php?element=' . rawurlencode($this->eRParts[0] . ':' . $this->eRParts[1]) . '&revert=ALL_FIELDS&sumUp=-1&returnUrl=' . rawurlencode($this->R_URI) . '\'; return false;') . '">' .
						'<img' . t3lib_iconWorks::skinImg($BACK_PATH, 'gfx/undo.gif', 'width="21" height="16"') . ' class="c-inputButton" title="' . htmlspecialchars(sprintf($LANG->getLL('undoLastChange'), t3lib_BEfunc::calcAge(time() - $this->undoButtonR['tstamp'], $LANG->sL('LLL:EXT:lang/locallang_core.php:labels.minutesHoursDaysYears')))) . '" alt="" />' .
						'</a>';
					
						// History button
					$buttons['history_record'] = '<a href="#" onclick="' . htmlspecialchars('jumpToUrl(\'' . $BACK_PATH . 'show_rechis.php?element=' . rawurlencode($this->eRParts[0] . ':' . $this->eRParts[1]) . '&returnUrl=' . rawurlencode($this->R_URI) . '#latest\');return false;') . '">' .
						'<img' . t3lib_iconWorks::skinImg($BACK_PATH, 'gfx/history2.gif', 'width="13" height="12"') . ' class="c-inputButton" title="' . $LANG->getLL('recordHistory', 1) . '" alt="" />' .
						'</a> ';
				}
			}
		}
		
		return $buttons;
	}
	
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/t3flextoolkit/mod2/index.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/t3flextoolkit/mod2/index.php']);
}


// Make instance:
$SOBE = t3lib_div::makeInstance('tx_t3flextoolkit_module2');
$SOBE->init();

// Include files?
foreach($SOBE->include_once as $INC_FILE)	include_once($INC_FILE);

$SOBE->main();
$SOBE->printContent();

?>