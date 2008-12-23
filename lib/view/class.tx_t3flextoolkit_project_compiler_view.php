<?php
/*
 * Created on 2 okt 2008
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 
 require_once ($TYPO3_MOD_PATH.'../lib/control/class.tx_t3flextoolkit_compiler_event.php');
 require_once ($TYPO3_MOD_PATH.'../lib/utils/file/class.tx_t3flextoolkit_class_writer.php');
 
class tx_t3flextoolkit_project_compiler_view {
 	
 	
 	var $model;
 	
 	function tx_t3flextoolkit_project_compiler_view() {
 	
 	 
 	}
 	
 	 function init($model) {
 	 		
 	
 				$this->model= $model;
 			}
 	
 	
 	function getCurrentView() {
 		
 		$this->model->compilerView=1;
 		if($this->model->compilerView==5) {
				 return $this->compilerError();
				}
 		
 		if(t3lib_div::GPVar('preview')) {
					$this->model->compilerView=2;
				}
 		if(t3lib_div::GPVar('compile')=='true') {
					$this->model->compilerView=3;
					
				}
				
 		
				
 		switch ($this->model->compilerView) {
			case 1:
					return $this->compiler();
				break;
			case 2:
					return $this->preview();
				break;
			case 3:
					return $this->run();
				break;
			
		}
 		
 	}
 	
 	


// This function uses a callback function.

function compilerError()
{
	$this->model->compilerView=1;
	echo '<br />COMPILER ERROR....'.$this->compiler();
    

}

// This function uses a callback function.

function startCompiler($callback)
{

    $this->$callback($this->compilerEvents->makeAntBuildFile());

}




// This is a sample callback function for doIt().

function myCallback($data)

{
	echo 'ant builded'.$data;
	
//	t3lib_div::debug($this->model->compilerView);
//		if($this->model->compilerView==5) {
//					 return $this->compiler();
//				} else {
//					 return $this->compiler();
//				}
	
	// echo '<script>document.location.href="'.t3lib_div::getIndpEnv('TYPO3_SITE_URL').'fileadmin/flexfiles/swf/index_'.$this->model->projectStartingRecord['uid'].'.html" </script>';
	//
	echo $this->compiler();
  	// 

}






var $compilerEvents;
 	
 	
 	function run() {	
 		
 			$staticParser = t3lib_div::makeInstance('tx_t3flextoolkit_class_writer');
			$staticParser->init($this->model);
			$staticParser->makeStatic();
 		
 		
 		t3lib_div::_GETset('false','ant');
 			
 	
		$this->compilerEvents = tx_t3flextoolkit_compiler_event::singleton();	
 		return $this->startCompiler('myCallback');
 	}
 	
 	function preview() {
 		return 'preview';
 	}
 	
 	function compiler() {
 			
 				//tx_t3ftoolkit_project_tree_util::GetTreeArrayFromCustomId($this->model->currentId);
 					
				//	t3lib_div::debug($this->model->projectProperties['data']['sDEF8']['lDEF']['templateFile']);
 					
 				
 				global $BE_USER,$LANG,$BACK_PATH,$TCA_DESCR,$TCA,$CLIENT,$TYPO3_CONF_VARS;	
 					
 					$mxmlc=trim($this->model->projectProperties['data']['sDEF']['lDEF']['defaultInput']['vDEF']);
					$target=trim($this->model->projectProperties['data']['sDEF3']['lDEF']['classpath']['vDEF']);
					$framerate=trim($this->model->projectProperties['data']['sDEF4']['lDEF']['framerate']['vDEF']);
						
					$uid= $this->model->projectId;
							
					$bgcolor=trim($this->model->projectProperties['data']['sDEF4']['lDEF']['bgcolor']['vDEF']);
					$width=trim($this->model->projectProperties['data']['sDEF4']['lDEF']['width']['vDEF']);
					$height=trim($this->model->projectProperties['data']['sDEF4']['lDEF']['height']['vDEF']);
					$deploydir=trim($this->model->projectProperties['data']['sDEF']['lDEF']['deploydir']['vDEF']);
					$antbasedir = trim($GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['absTypo3FileDocRoot']);
					$options = trim($this->model->projectProperties['data']['sDEF']['lDEF']['coptions']['vDEF']);
							
					$this->thetime = date("dHis");	
					$fulldeploydir=trim($antbasedir.$deploydir.'page_'.$this->model->projectId.'.swf');
					$fulltargetdir=trim($antbasedir.$target);
							
					$this->fulldeploydir=trim($fulldeploydir);
						
					$sourcepath=trim($this->model->projectProperties['data']['sDEF3']['lDEF']['sourcepaths']['vDEF']);
 				
 					$columnClass="c-headLine";
 					$_output = '<br />Project '.$this->model->projectStartingRecord['title'].'<br /><br />absolute path to the compiler:<b>' .$mxmlc.'</b><br />Main target class:	<b>' .$target.
 							'</b><br />' .
 							'<br /><tr  nowrap="nowrap">
						<td class="'.$columnClass.'">icon</td>
						<td class="'.$columnClass.'">application</td>
						<td class="'.$columnClass.'">uid</td>
						<td class="'.$columnClass.'">compile</td>
						<td class="'.$columnClass.'">preview</td>
						<td class="'.$columnClass.'">ant</td>
						<td class="'.$columnClass.'">success</td>
						<td class="'.$columnClass.'">swf filetime</td>
						<td class="'.$columnClass.'">change settings</td></tr>';
	
		
			
				 //t3lib_div::debug( $GLOBALS['TSFE']->getPagesTSconfig());
		
		
		
	$output=$this->wait().'<div style="width:800;"><table class="typo3-page-pages" border="0" cellpadding="2" cellspacing="2">
'.$_output.$this->getInterFace().'</table></div><br/><br/>';
 					return $output;
 			}
 			
 			
 		
 		
 	function wait() {

 $javaScript = <<<HERE
<script type="text/javascript">

function replace() {

document.getElementById('foo').innerHTML = 'test';

}
</script>


HERE;
 
 	
 		
	
	return $javaScript;
 	}
 		
 	function getInterFace() {	
 		
 		if ($this->model->projectStartingRecord['doktype']==$GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['registertodoktypesPROJECT']) {
 					$filename = $GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['absTypo3FileDocRoot'].$this->model->projectProperties['data']['sDEF']['lDEF']['deploydir']['vDEF'].'page_'.$this->model->projectStartingRecord['uid'].'.swf';
					$theIcon = 'typo3conf/ext/t3flextoolkit/icons/flexicon.gif';
					if (file_exists($filename)) {
				
						$SwfExists = true;
						$fileTime= date("F d Y H:i:s", filectime($filename));
					
					return $this->getAppPageTreeRow($theIcon,$this->model->projectStartingRecord['uid'],$this->model->projectStartingRecord['title'], '#D7DBE2',
 						'<td class="bgColor5"><img src="'.t3lib_div::getIndpEnv('TYPO3_SITE_URL').'typo3/gfx/icon_ok.gif"/></td><td  class="bgColor5">['.$fileTime.']</td>');
						
					} else {
	return $this->getAppPageTreeRow($theIcon,$this->model->projectStartingRecord['uid'],$this->model->projectStartingRecord['title'], '#D7DBE2',
 						'<td class="bgColor5"><img src="'.t3lib_div::getIndpEnv('TYPO3_SITE_URL').'typo3/gfx/icon_fatalerror.gif"/></td><td  class="bgColor5">no swf</td>');
					}
 				
 	}
 	}

 
 	function getAppPageTreeRow($thePageIcon, $uid, $title, $color, $icon) {
			
			$button = "height:20px;";
			
			$filetime = ' ';
			$filename =  trim($GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['absTypo3FileDocRoot']).$this->model->projectProperties['data']['sDEF']['lDEF']['deploydir']['vDEF']."page_".$uid.".swf";
			
			if (file_exists($filename)) {
				$filetime= '<td width="50px" style="background-color:lightgreen;" nowrap="nowrap"> ok '.date("H:i:s.", filemtime($filename)).'</td>';
			} else {
				$filetime= '<td width="50px" style="background-color:red;" nowrap="nowrap">no files</td>';
			}
		 
			$swfPreview=t3lib_div::getIndpEnv('TYPO3_SITE_URL').$this->deploydir.'page_'.$this->model->projectStartingRecord['uid'].'.swf';
			$antPreview=t3lib_div::getIndpEnv('TYPO3_SITE_URL').'fileadmin/flexfiles/build_local_'.$this->model->projectStartingRecord['uid'].'.xml';
			
			$output = ' <br/> <tr class="bgColor4">
			
			<td class="bgColor5"><img src="'.t3lib_div::getIndpEnv('TYPO3_SITE_URL').$thePageIcon.'" border=0 width="18" height="16"/><img width="10px" height="10px" src="'.t3lib_div::getIndpEnv('TYPO3_SITE_URL').'clear.gif"></td>
			<td class="bgColor5">'.$this->model->projectStartingRecord['title'].'</td>
			<td class="bgColor5">'.htmlspecialchars($this->model->projectStartingRecord['uid']).'</td>
			<td class="bgColor5"><div style="'.$button.'"><a href="'.t3lib_div::getIndpEnv('TYPO3_SITE_URL').'typo3conf/ext/t3flextoolkit/mod1/'.'index.php?id='.$this->model->projectStartingRecord['uid'].'&compile=true"><img border="0" onmouseup="this.src='."'".'16.gif'."'".'" src="'.t3lib_div::getIndpEnv('TYPO3_SITE_URL').'typo3conf/ext/t3flextoolkit/mod1/build.gif"></a></div></td>
			<td class="bgColor5"><div style="'.$button.'"><a href="'.t3lib_div::getIndpEnv('TYPO3_SITE_URL').$this->model->projectProperties['data']['sDEF']['lDEF']['deploydir']['vDEF'].'index_'.$this->model->projectStartingRecord['uid'].'.html"> <img border="0" src="'.t3lib_div::getIndpEnv('TYPO3_SITE_URL').'typo3conf/ext/t3flextoolkit/mod1/module_web_view.gif"> </a> </div></td>
			<td class="bgColor5"><div style="'.$button.'"><a href="'.$antPreview.'"> <img border="0" src="'.t3lib_div::getIndpEnv('TYPO3_SITE_URL').'typo3conf/ext/t3flextoolkit/mod1/module_web_view.gif"> </a></div> </td>
			'.$icon.'
			<td class="bgColor5"> <div style="'.$button.'"><a href="'.t3lib_div::getIndpEnv('TYPO3_SITE_URL').'typo3/alt_doc.php?edit[pages]['.$this->model->projectId.']=edit"><image src="../../../../typo3/sysext/t3skin/icons/gfx/edit2.gif" ></a></td>
			</tr>';
			return $output;
 	}
 	
 
}	
 
?>
