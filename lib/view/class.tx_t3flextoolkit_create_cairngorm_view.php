<?php
/*
 * Created on 2 okt 2008
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 


if($_GET['preview']) {
		echo tx_t3flextoolkit_create_cairngorm_view::preview();
}


class tx_t3flextoolkit_create_cairngorm_view {
 	
 	
 	var $model;
 	var $content;
 	
 	function tx_t3flextoolkit_create_cairngorm_view() {
 		
 		
 		
			

 	}
 	
 	
 	 function getClassFromUrl($className,$url) {
 	 
 	
 	 
 	 $flexform='<?xml version="1.0" encoding="iso-8859-1" standalone="yes" ?>
<T3FlexForms>
    <data>
        <sheet index="sDEF">
            <language index="lDEF">
                <field index="templatefiles">
                    <value index="vDEF"></value>
                </field>
                <field index="editor">
                    <value index="vDEF">'.htmlspecialchars(t3lib_div::getURL($url)).'</value>
                </field>
            </language>
        </sheet>
        <sheet index="sDEF2">
            <language index="lDEF">
                <field index="comments">
                    <value index="vDEF">imported from url: '.$url.'</value>
                </field>
            </language>
        </sheet>
    </data>
</T3FlexForms>';

return $flexform;
 	 }
 	
 	
 	
 	 function init($model) {
 				$this->model= $model;
 			}
 	
 
 	function getCurrentView() {
 		
 	if(t3lib_div::_GET('makeFrameWork')) {
 		$this->makeFrameWork();
 	}
 			
			
				if(t3lib_div::_GET('createClass')) {
				
						$extStr=$_GET['template'];
						$ext = array();
						preg_match("/mxml/", $extStr, $ext, PREG_OFFSET_CAPTURE, 3);
					
						
					
							if ($ext['0']['0']=='mxml') {
								$classType=$GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['registertodoktypesMXML'];
							} else {
								$classType=$GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['registertodoktypesACTIONSCRIPT'];
							}
		
								
							$className = t3lib_div::_GP('className');						
							$classRole = $this->model->currentPageInfo['title'];
							$templateName=  t3lib_div::_GP('pageid');
							$url= t3lib_div::_GP('template');
						 	
							$this->makeClass($classType,$classRole,$templateName,$className,$url,$_GET['id']);

					
							//t3lib_div::debug($_GET);

							$_GET["className"] = '';
							t3lib_BEfunc::getSetUpdateSignal('updatePageTree');

							}
				
 		switch ($this->model->cairngormCreatorView) {
			case 1:
					return $this->model->cairngormCreatorView.$this->form2();
				break;
			case 2:
					return $this->model->cairngormCreatorView.$this->preview();
				break;		
		}
 		return "";
 	}
 	


function ajaxTest() {
 			
 			echo 'ajaxTest';
 			
 			
 			//.tx_t3flextoolkit_ajax_event::saveCode().$this->form();
 	 	
 	
 	}

 	function preview() {
 			
 			echo 'PREVIEW';
 			
 			
 			//.tx_t3flextoolkit_ajax_event::saveCode().$this->form();
 	 	
 	
 	}
 	
 	function form() {

 $javaScript = <<<HERE
<script type="text/javascript">

function replace() {

document.getElementById('foo').innerHTML = 'compiling.... <image src=16.gif />';

}
</script>

<p><a href="javascript:replace()">compile</a></p>

<div id="foo">

</div>
HERE;
 
 	
 		$this->content .= $javaScript;
	
	
	return $this->content;
 	}
	
 	
 	function form2() {
 			
	
		
$comName	= $this->model->comName;
$Name	=	$this->model->domainName;;

$classPath = $comName.".".$Name;
$delegateClassName = "typo3Service";


 	$url= t3lib_div::getIndpEnv('TYPO3_SITE_URL')."typo3conf/ext/t3flextoolkit/cm2/index.php?id=".t3lib_div::_GP('id').'&createClass=true&testName=testnameclass';
  
	
	$this->content ='
	
	<br/>
	<table  cellspacing="0" cellpadding="5" class="bgColor5" border="0" >
  <tr><td><input type="submit" onclick="document.location='."'".'index.php?id='.$this->model->currentId."&makeFrameWork=1'".'" name="makeFrameWork" value="make whole new Cairngorm framework">

    </td></tr><tr><td  class="bgColor5" >This option creates the Cairngorm tree dirs: ['.$classPath.']  and classes for a new application.</td></tr>
  
</table>
		<br/><br/>
	';


	$this->content .=$this->getTemplateSelector();

 	return $this->content;
 	
 	
 			}
 
 
 function importClass($cbArray) {
	$mijnLijst= '';
	
		foreach($cbArray as $data) {
					$mijnLijst .= '<br/>';
		}
					return $mijnLijst;
	
	}

 function makeClass($classType,$classRole,$templateName,$className,$url,$targetId) {
	
		
					
$tce = t3lib_div::makeInstance('t3lib_TCEmain');
$tce->stripslashes_values = 0;
$tce->reverseOrder = 1;
$tce->start($data,array());
$tce->process_datamap();
$tce->process_cmdmap();



$data = array(
    'pages' => array(
        'NEW_1' => array(
     	      "title" => $className,
  			  "pid" => $targetId,
			  "hidden" => 0,
			  "doktype" => $classType,
			  "tx_flextoolkit_ds" => $this->getClassFromUrl($className,$url),
          ),
    )
);

$tce->stripslashes_values = 0;
$tce->reverseOrder = 1;
$tce->start($data,array());
$tce->process_datamap();
$tce->process_cmdmap();
$newuid=$tce->substNEWwithIDs;

$tce->clear_cacheCmd('pages');

	
	

	
			
						
echo '<script>document.location.href="'.t3lib_div::getIndpEnv('TYPO3_SITE_URL').'typo3conf/ext/t3flextoolkit/mod2/index.php?id='.$newuid[NEW_1].'&refreshtree=true" </script>';
	
	
	}
 
 		
 	function makeFrameWork() {
 		$data = array(

    'pages' => array(

        'NEW_1' => array(

     	      "title" => $this->model->comName,
  			  "subtitle" => trim($this->model->comName),
  			  "pid" => $this->model->projectId,
			  "hidden" => 0,
			  "doktype" => 254,
          ),

       

    )

);
 $tce = t3lib_div::makeInstance('t3lib_TCEmain');
$tce->stripslashes_values = 0;
$tce->reverseOrder = 1;
$tce->start($data,array());
$tce->process_datamap();
$tce->process_cmdmap();


$newuid=$tce->substNEWwithIDs;

$data = array(

    'pages' => array(

        'NEW_2' => array(

     	      "title" =>  $this->model->domainName,
  			  "subtitle" => trim($this->model->domainName),
  			  "pid" => $tce->substNEWwithIDs['NEW_1'],
			  "hidden" => 0,
			  "doktype" => 254,
          ),

       

    )

);

$tce->stripslashes_values = 0;
$tce->reverseOrder = 1;
$tce->start($data,array());
$tce->process_datamap();
$tce->process_cmdmap();

$data = array(

    'pages' => array(

        'NEW_3' => array(
     	      "title" => 'business',
  			  "subtitle" => 'business',
  			  "pid" => $tce->substNEWwithIDs['NEW_2'],
			  "hidden" => 0,
			  "doktype" => 254,
          ),
		'NEW_4' => array(
     	      "title" => 'command',
  			  "subtitle" => 'command',
  			  "pid" => $tce->substNEWwithIDs['NEW_2'],
			  "hidden" => 0,
			    "doktype" => 254,
          ),
          'NEW_5' => array(
     	      "title" => 'control',
  			  "subtitle" => 'controll',
  			  "pid" => $tce->substNEWwithIDs['NEW_2'],
			  "hidden" => 0,
			    "doktype" => 254,
          ),
       'NEW_6' => array(
     	      "title" => 'model',
  			  "subtitle" => 'model',
  			  "pid" => $tce->substNEWwithIDs['NEW_2'],
			  "hidden" => 0,
			   "doktype" => 254,
          ),
        'NEW_7' => array(
     	      "title" => 'view',
  			  "subtitle" => 'view',
  			  "pid" => $tce->substNEWwithIDs['NEW_2'],
			  "hidden" => 0,
			    "doktype" => 254,
          ),
          'NEW_8' => array(
     	      "title" => 'vo',
  			  "subtitle" => 'vo',
  			  "pid" => $tce->substNEWwithIDs['NEW_2'],
			  "hidden" => 0,
			    "doktype" => 254,
          ),
),
     'tt_content' => array(

        'NEW_213123123' => array(
     	      	"title" => 'business_service',
				"bodytext" => '',
  			 	"pid" => $tce->substNEWwithIDs['NEW_1'],
			  	"colPos" => 0,
          ),
	
		),
	);

$tce->stripslashes_values = 0;
$tce->reverseOrder = 1;
$tce->start($data,array());
$tce->process_datamap();
$tce->process_cmdmap();

$tce->clear_cacheCmd('pages');
 		
 	}
 	
 	
 	
 	function getDBTemplates() {
		
		$baseDir = trim($GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['absTypo3FileDocRoot']) . 'typo3conf/ext/t3flextoolkit/lib/codetemplates';
	
			
		
		$res='<select name="template">';
		$result =t3lib_div::getFilesInDir  	($baseDir,t3lib_div::GPvar('templateType'))  ;
	
	
	
		foreach($result as $file) {
			
			
			$res.='<option value="'.t3lib_div::getIndpEnv('TYPO3_SITE_URL').'typo3conf/ext/t3flextoolkit/lib/codetemplates/'.$file.'" selected="selected">'.$file.'</option>';
		}
		
		return $res.'</select>';
		
	}
 	
 		function getTemplates() {
		
		$baseDir = trim($GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['absTypo3FileDocRoot']) . 'typo3conf/ext/t3flextoolkit/lib/codetemplates/';
	
			
		
			$res='<select name="template">';
		if (t3lib_div::GPvar('templateType')) { $templateType=t3lib_div::GPvar('templateType');} else { $templateType='mxml,as';
		}
		
		$result=t3lib_div::getAllFilesAndFoldersInPath(array(),
		$baseDir,$templateType
		
	)  ;
	$result=t3lib_div::removePrefixPathFromList($result,$baseDir);
	//t3lib_div::debug($result );
	
		foreach($result as $file) {
			
			
			$res.='<option value="'.rawurlencode(t3lib_div::getIndpEnv('TYPO3_SITE_URL').'typo3conf/ext/t3flextoolkit/lib/codetemplates/'.$file).'" selected="selected">'.$file.'</option>';
		}
		
		return $res.'</select>';
		
	}
	
	
	
 		function getTemplateSelector()	{
		
$this->content.='
<br/>
<table cellspacing="0" cellpadding="5" class="bgColor5" border="0" >
  <tr><tr>
    <td  class="bgColor5" colspan="3" align="center">Template based codegeneration:</td>
  </tr>
 <td width="150">Make new Class</td>
    <td><input  type="input" size="30" name="className" value="[className]"  onfocus="this.value='."''".'"><input  type="hidden" name="pageid" value="'.$this->model->currentId.'"  ></td>
      <td>'.$this->getTemplates().'</td>
    </tr>
   <tr>
      <td>&nbsp;</td>
    <td  align="left"><input  type="hidden" size="30" name="createClass" value="false" />
    <input type="submit" onclick="document.location='."'index.php?id=".$this->model->currentId."&createClass=true&className='+document.editform.className.value+'&template='+document.editform.template.value".'; return false;" 
    value="create class">
    </td>
  </tr>
</table><BR/>select template class first, fill in the new class name then save the results. Do not use .mxml or .as extension in class field';


	

							
	}
	
	
	
		
	
}	
 
?>
