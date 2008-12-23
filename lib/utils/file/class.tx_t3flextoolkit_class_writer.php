<?php
/*
 * Created on 2 okt 2008
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 
require_once ($BACK_PATH."../typo3conf/ext/t3flextoolkit/lib/utils/templating/class.tx_t3flextoolkit_dynamicToStaticCodeParser.php");
require_once ($BACK_PATH."../typo3conf/ext/t3flextoolkit/lib/model/class.tx_t3flextoolkit_model.php");
require_once(PATH_t3lib.'class.t3lib_pagetree.php');

class tx_t3flextoolkit_class_writer {
 	
 	var $treeIter;
 	var $model;
 	
 	function tx_t3flextoolkit_class_writer() {
 	
 	 
 	}
 	
 	
 	function make_dirs($baseDir, $deepDir) {
		return t3lib_div :: mkdir_deep($baseDir . "/", $deepDir . "/");
	}

	function replace_backslash($str_to_detect, $str_replace) {
		if ($this->chrs_is_all_readable($str_to_detect)) {
			return (preg_replace('[\/]', $str_replace, $str_to_detect));
		}
	}
	
	function chrs_is_all_readable($string) {
		for ($i = 0; $i < strlen($string); $i++) {
			$chr = $string {
				$i };
			$ord = ord($chr);
			if ($ord < 32 or $ord > 126)
				return (false);
		}
		return (true);
	}
 	
 	 function init($model) {
 				$this->model= $model;
 				
 				$this->treeIter = t3lib_div::makeInstance('tx_t3flextoolkit_project_tree_util');
				$this->treeIter->init($this->model);
 			}
 	
 	function makeStaticService() {
 	
 //	t3lib_div::debug($_GET);t3lib_div::debug($_POST);
 				$this->model->init($this->model->currentId);
 						



	$baseDir = trim($GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['absTypo3FileDocRoot']) . 'fileadmin/flexfiles/services';

							
$deepDir = dirname(t3lib_befunc :: getRecordPath($this->model->currentId, '', '', 0));							
$deepDir = str_replace(" ","_",$deepDir);
$this->make_dirs($baseDir, $deepDir);
$filename = $baseDir.$deepDir.'/'.$this->model->currentPageInfo['title'].'.'.$this->model->codeExt;
$templateParser = tx_t3flextoolkit_dynamicToStaticCodeParser::singleton();
$code = $templateParser->makeStatic($this->model->codeString);
	
		
		t3lib_div :: writeFile($filename, $code);
		chmod($filename, 0777);
		
							
					 	
 		
 		$this->model->init($this->model->currentId);
 		
 		
 		
 		
 		
 		
 		
 		
 		
 		
 			}
	 	
 	function makeStatic() {
 	
 		$tree = $this->treeIter->projectTreeIterator($this->model->projectId);
 		
 		$currentId = $this->model->projectId;
 		
 		
 		
 			foreach($tree as $data) {
 				$this->model->init($data);
 							if ($data == $currentId) { 
 								// project id is geen code, overslaan dus
 							} else {


if($this->model->codeExt=='php') {
	
	$baseDir = trim($GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['absTypo3FileDocRoot']) . 'fileadmin/flexfiles/services';
} else {

							
$baseDir = trim($GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['absTypo3FileDocRoot']) . 'fileadmin/flexfiles/projectsource';
$deepDir = dirname(t3lib_befunc :: getRecordPath($data, '', '', 0));							
$deepDir = str_replace(" ","_",$deepDir);

}


if ($this->model->currentPageInfo['doktype']==254) {
	//t3lib_div::debug($deepDir.'/'.$this->model->currentPageInfo['title']);
	$this->make_dirs($baseDir, $deepDir.'/'.$this->model->currentPageInfo['title']);
} else {

$this->make_dirs($baseDir, $deepDir);


$filename = $baseDir.$deepDir.'/'.$this->model->currentPageInfo['title'].'.'.$this->model->codeExt;

		
		$templateParser = tx_t3flextoolkit_dynamicToStaticCodeParser::singleton();
		$code = $templateParser->makeStatic($this->model->codeString);
		//t3lib_div::debug($code);
		
		t3lib_div :: writeFile($filename, $code);
		chmod($filename, 0777);
		//	t3lib_div::debug($this->model->currentPageInfo);
 							}
							
					 	
					 }
 	
 		
 		$this->model->init($currentId);
 		
 		
 		}
 		
 		
 		
 		
 			
 		}
 	
 	
 	}
 	
 	 	
