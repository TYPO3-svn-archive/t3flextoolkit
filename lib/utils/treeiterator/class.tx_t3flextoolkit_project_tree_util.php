<?php
/*
 * Created on 2 okt 2008
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 
require_once($TYPO3_MOD_PATH.'../lib/model/class.tx_t3flextoolkit_model.php'); 
require_once(PATH_t3lib.'class.t3lib_pagetree.php');

class tx_t3flextoolkit_project_tree_util {
 	
 	
 	var $model;
 	
 	function tx_t3flextoolkit_project_tree_util() {
 	
 	 
 	}
 	
 	 function init($model) {
 				$this->model= $model;
 			}
 	
 				
 	function GetTreeArrayFromCustomId($id) {
 		
 			
				$treeStartingPoint =  $id;
				
				if (!$treeStartingPoint) {$treeStartingPoint=0;}
				$treeStartingRecord = t3lib_BEfunc::getRecord('pages', $treeStartingPoint);
				$depth = 10;
				 

				$tree = t3lib_div::makeInstance('t3lib_pageTree');
				$tree->init('AND '.$GLOBALS['BE_USER']->getPagePermsClause(1));
				 

				$HTML = t3lib_iconWorks::getIconImage('pages', $treeStartingRecord, $GLOBALS['BACK_PATH'], 'align="top"');
				 
				$tree->tree[] = array(
				'row' => $treeStartingRecord,
					'HTML' => $HTML,
					);
				 
				$tree->getTree($treeStartingPoint, $depth, ''); 
				
				
				 
					
 		
 		return $tree->tree;
 	}
 	function treeStartFromCustomId($id) {
 		
 			
				$treeStartingPoint =  $id;
				
				if (!$treeStartingPoint) {$treeStartingPoint=0;}
				$treeStartingRecord = t3lib_BEfunc::getRecord('pages', $treeStartingPoint);
				$depth = 10;
				 

				$tree = t3lib_div::makeInstance('t3lib_pageTree');
				$tree->init('AND '.$GLOBALS['BE_USER']->getPagePermsClause(1));
				 

				$HTML = t3lib_iconWorks::getIconImage('pages', $treeStartingRecord, $GLOBALS['BACK_PATH'], 'align="top"');
				 
				$tree->tree[] = array(
				'row' => $treeStartingRecord,
					'HTML' => $HTML,
					);
				 
				$tree->getTree($treeStartingPoint, $depth, ''); 
				//t3lib_div::debug($tree->tree);
				$mijnLijst='';
				 
					foreach($tree->tree as $data) {
							
							$mijnLijst .= '<br />'.$data['row']['title'];
							
					 	
					 }
 		
 		return $mijnLijst;
 	}
 	
 	// executes custom actions
 	function projectTreeIterator() {
 		
 			
				$treeStartingPoint =  $this->model->projectId;
				
				if (!$treeStartingPoint) {$treeStartingPoint=0;}
				$treeStartingRecord = t3lib_BEfunc::getRecord('pages', $treeStartingPoint);
				$depth = 10;
				 

				$tree = t3lib_div::makeInstance('t3lib_pageTree');
				$tree->init('AND '.$GLOBALS['BE_USER']->getPagePermsClause(1));
				 

				
				$HTML = t3lib_iconWorks::getIconImage('pages', $treeStartingRecord, $GLOBALS['BACK_PATH'], 'align="top"');
				 
				$tree->tree[] = array(
				'row' => $treeStartingRecord,
					
					);
				 
				$tree->getTree($treeStartingPoint, $depth, ''); 
				
				$mijnLijst=array();
				 
					foreach($tree->tree as $data) {
							
							$mijnLijst []= $data['row']['uid'];
							
					 	
					 }
 		
 		return $mijnLijst;
 	}
 	
 	// executes custom actions
 	function treeStartFromCurrentUid() {
 		
 			
				$treeStartingPoint =  $this->model->currentId;
				
				if (!$treeStartingPoint) {$treeStartingPoint=0;}
				$treeStartingRecord = t3lib_BEfunc::getRecord('pages', $treeStartingPoint);
				$depth = 10;
				 

				$tree = t3lib_div::makeInstance('t3lib_pageTree');
				$tree->init('AND '.$GLOBALS['BE_USER']->getPagePermsClause(1));
				 

				$HTML = t3lib_iconWorks::getIconImage('pages', $treeStartingRecord, $this->BACK_PATH, 'align="top"');
				 
				$tree->tree[] = array(
				'row' => $treeStartingRecord,
					'HTML' => $HTML,
					);
				 
				$tree->getTree($treeStartingPoint, $depth, ''); 
				//t3lib_div::debug($tree->tree);
				$mijnLijst='';
				 
					foreach($tree->tree as $data) {
							//t3lib_div::debug($tree->tree);
							$mijnLijst .= '<br />'.$data['row']['title'];
							
					 	
					 }
 		
 		return $mijnLijst;
 	}
 	 	
}	
 
?>
