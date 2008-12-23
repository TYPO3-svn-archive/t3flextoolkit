<?php
/*
 * Created on 2 okt 2008
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 
 
require_once($TYPO3_MOD_PATH.'../lib/model/class.tx_t3flextoolkit_model.php');
require_once(PATH_t3lib.'class.t3lib_pagetree.php');
require_once (PATH_t3lib.'class.t3lib_basicfilefunc.php');
require_once (PATH_t3lib.'class.t3lib_extfilefunc.php');
require_once (PATH_t3lib.'class.t3lib_recordlist.php');
require_once (PATH_t3lib.'class.t3lib_clipboard.php');
require_once (PATH_t3lib.'class.t3lib_parsehtml.php');
require_once ($TYPO3_MOD_PATH.'../lib/class.file_classlist.inc');

class tx_t3ftoolkit_class_importer {
 	
 	
 	var $model;
 	var $basicFF;
 	var $filelist;
 	var $pointer;
 	
 	
 	var $MCONF=array();			// Module configuration
	var $MOD_MENU=array();
	var $MOD_SETTINGS=array();
	
 	function tx_t3ftoolkit_class_importer() {
 	
 	 
 	}
 	
 	
	
 	 function init() {
 	 	
 		global $TYPO3_CONF_VARS,$FILEMOUNTS,$BACK_PATH;

			// Setting GPvars:
		$this->id = t3lib_div::_GP('id');
		$this->pointer = t3lib_div::_GP('pointer');
		$this->table = t3lib_div::_GP('table');
		$this->imagemode = t3lib_div::_GP('imagemode');
		$this->cmd = t3lib_div::_GP('cmd');
		$this->overwriteExistingFiles = t3lib_div::_GP('overwriteExistingFiles');
 	
 		$path =$GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['absTypo3FileDocRoot'].'fileadmin/flexFiles/src/'.$this->model->projectStartingRecord['title'];
 		$this->pointer = $path;
 					// File operation object:
		$GLOBALS['SOBE']->basicFF = t3lib_div::makeInstance('t3lib_basicFileFunctions');
		$GLOBALS['SOBE']->basicFF->init($FILEMOUNTS,$TYPO3_CONF_VARS['BE']['fileExtensions']);
 		
 		
 		$this->id = $GLOBALS['SOBE']->basicFF->is_directory($path);
		//$access = $this->id && $GLOBALS['SOBE']->basicFF->checkPathAgainstMounts($path.'/');
 	
 			// Create filelisting object
			$this->filelist = t3lib_div::makeInstance('fileList');
			
			$this->filelist->backPath = '../../../../typo3/';
			$this->filelist->thumbs = 0;

				// Create clipboard object and initialize that
			$this->filelist->clipObj = t3lib_div::makeInstance('t3lib_clipboard');
			$this->filelist->clipObj->fileMode=1;
			$this->filelist->clipObj->initializeClipboard();
			$this->filelist->clipObj->endClipboard();// Saves			
			$this->pointer = t3lib_div::intInRange($this->pointer,0,100000);
			$this->filelist->start($path,$this->pointer,'file',0,1,1);
				// Set top JavaScript:
			$this->model->JScode='

			if (top.fsMod) top.fsMod.recentIds["file"] = unescape("'.rawurlencode($this->id).'");
			function jumpToUrl(URL)	{	//
				window.location.href = URL;
			}

			'.$this->filelist->CBfunctions();
			

				// Generate the list
			$this->filelist->generateList();
			$this->filelist->HTMLcode=str_replace('"sysext','"../../../../typo3/sysext',$this->filelist->HTMLcode);
				// Write the footer
		//	$this->filelist->writeBottom();
			
			
			//t3lib_div::debug( $this->filelist);
		
				
 	 }
 	 
 	 
 	 function getHtml() {
 	 	
 	 	return $this->filelist->HTMLcode;
 	 }
 	
 	 function init3($model) {
 				$this->model= $model;
 				
 				$clipObj = t3lib_div::makeInstance('t3lib_clipboard');        // Start clipboard

   // $clipObj->initializeClipboard();    // Initialize - reads the clipboard content from the user session
$clipObj->fileMode=1;
$clipObj->initializeClipboard();
t3lib_div::debug($clipObj);
t3lib_div::debug($clipObj->elFromTable('_FILE'),'Files available:');

   t3lib_div::debug($clipObj->elFromTable('pages'),'Page records:');

    $clipObj->setCurrentPad('normal');

    echo 'Changed to "normal" pad...';

    t3lib_div::debug($clipObj->elFromTable('_FILE'),'Files available:');

   t3lib_div:: debug($clipObj->elFromTable('pages'),'Page records:');


   $CB = t3lib_div::_GET('CB');    // CB is the clipboard command array

 		t3lib_div::debug($CB);
 			}
 	
 	function getProjectFiles() {
 		
 		if (!$this->model->projectStartingRecord['title']=='') {
 			$path =$GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['absTypo3FileDocRoot'].'fileadmin/flexFiles/src/'.$this->model->projectStartingRecord['title'].'';
 			return $this->getFiles($path,'mxml,as,xml,css');
 		} else  {
 			return array('no files');
 		}
 			
 		
 	}
 	
 	function getDeployDir() {
 		
 		if (!$this->model->projectStartingRecord['title']=='') {
 			$path =$GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['absTypo3FileDocRoot'].'fileadmin/flexFiles/swf';
 			return $this->getFiles($path,'xml,swf');
 		} else  {
 			return array('no files');
 		}
 			
 		
 	}
 	
 	function getFiles($path,$list) {
 		$allFiles= array(); 		
		$allFiles=t3lib_div::getAllFilesAndFoldersInPath($allFiles,$path,$list,1000,99);
	
 					return $allFiles;
	
 		}
 	

 	
}	
 
?>
