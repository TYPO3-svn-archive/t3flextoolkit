<?php




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
                    <value index="vDEF">'.t3lib_div::getURL($url).'</value>
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
t3lib_div::debug($flexform);
return $flexform;
 	 }
 	
 	
 	
 	 function init($model) {
 				$this->model= $model;
 			}
 	
 	
 	function getCurrentView() {
 		
 	
 				if(t3lib_div::GPVar('preview')) {
					$this->model->cairngormCreatorView=2;
				}
				
				if(t3lib_div::GPVar('makeClass')) {
					
					$this->makeFrameWork();
					 
					//t3lib_div::debug("makeClass");
				
				
				}
				
				
				
				if(t3lib_div::_GP('className')<>'') {
						
						
							if (split('.',$_GET['template']=='mxml')) {
								$classType=$GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['registertodoktypesMXML'];
							} else {
								$classType=$GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['registertodoktypesACTIONSCRIPT'];
							}
		
								
							$className = t3lib_div::_GP('className');						
							$classRole = $this->model->currentPageInfo['title'];
							$templateName=  t3lib_div::_GP('pageid');
							$url= t3lib_div::_GP('template');
							
							$this->makeClass($classType,$classRole,$templateName,$className,$url);





							$_GET["className"] = '';
							}
				
				
 		switch ($this->model->cairngormCreatorView) {
			case 1:
					return $this->model->cairngormCreatorView.$this->form2();
				break;
			case 2:
					return $this->model->cairngormCreatorView.$this->preview();
				break;		
		}
 		
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
  
	$this->content = $classPath;
	$this->content .='
	
	<br/>
	
	<input type="submit" onclick="document.location='."'".'index.php?id='.$this->model->currentId."&makeFrameWork=1'".'" name="makeClass" value="make whole new Cairngorm framework">
	<br/>This option creates the Cairngorm tree dirs and classes for a new application.<br/>
	';
	
	$this->content .=$this->getTemplateSelector();

 	return $this->content;
 	
 	
 			}
 
 
 function makeClass($classType,$classRole,$templateName,$className,$url) {
	
		require_once(PATH_t3lib.'class.t3lib_pagetree.php');
				$treeStartingPoint =  $this->model->projectId;				
			
				
				$treeStartingRecord = t3lib_BEfunc::getRecord('pages', $treeStartingPoint);
				$depth = 10;
				$tree = t3lib_div::makeInstance('t3lib_pageTree');
				$tree->init('AND '.$GLOBALS['BE_USER']->getPagePermsClause(1));
				$HTML ='';
				 
				$tree->tree[] = array(
				'row' => $treeStartingRecord,
					'HTML' => $HTML,
					);
				 

				$tree->getTree($treeStartingPoint, $depth, '');
				 

				 
				$output = '';
				 

				$mijnLijst = array();
				 $targetCount=0;
				foreach($tree->tree as $data) {
					$mijnLijst[] =array($data['row']['uid'],$data['row']['title']);
		
					if ($data['row']['title'] == $classRole) {
						$targetId = $data['row']['uid'];
						$targetCount++;
					}  
					
					}
					if ($targetId &&  $targetCount==1) {
								t3lib_div::debug($targetId);
								 $tce = t3lib_div::makeInstance('t3lib_TCEmain');
$tce->stripslashes_values = 0;
$tce->reverseOrder = 1;
$tce->start($data,array());
$tce->process_datamap();
$tce->process_cmdmap();



$data = array(
    'pages' => array(
        'NEW_2' => array(
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
t3lib_BEfunc::getSetUpdateSignal('updatePageTree');
$tce->clear_cacheCmd('pages');

								
							} else {
							if ($targetCount >1)  {
								 t3lib_div::debug('MORE THEN ONE VIEW FOLDER!');
							} else {
								 t3lib_div::debug('NO FRAMEWORK YET...');
							 }
						}
	
	
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


t3lib_div::debug($tce->substNEWwithIDs);
$newuid=$tce->substNEWwithIDs;

t3lib_div::debug($newuid);
$data = array(

    'pages' => array(

        'NEW_2' => array(

     	      "title" =>  $this->model->domainName,
  			  "subtitle" => trim($this->model->domainName),
  			  "pid" => $tce->substNEWwithIDs['NEW_1'],
  			  "uid" => 94,
			  "hidden" => 0,
			 
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



t3lib_div::debug($tce->substNEWwithIDs);
//$this->makeClass($GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['registertodoktypesACTIONSCRIPT'],'model','[AppModelLocator]','AppModelLocator');
//$this->makeClass($GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['registertodoktypesACTIONSCRIPT'],'control','[AppController]','AppController');
//$this->makeClass($GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['registertodoktypesMXML'],'business','[ServicesTemplate]','Services');
//$this->makeClass($GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['registertodoktypesMXML'],'view','[viewTemplate]','MainView');

//$this->content.='create cairngorn FrameWork in '.$this->target.' folder?';


t3lib_BEfunc::getSetUpdateSignal('updatePageTree');

$tce->clear_cacheCmd('pages');
 		
 	}
 	
 	
 		function getTemplates() {
		
		$baseDir = trim($GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['absTypo3FileDocRoot']) . 'typo3conf/ext/t3flextoolkit/lib/codetemplates';
	
			
		
			$res='<select name="template">';
		$result =t3lib_div::getFilesInDir  	(   	$baseDir,
		'mxml,as'
		
	)  ;
	
	//t3lib_div::debug($result );
			
		foreach($result as $file) {
			
			
			$res.='<option value="'.$baseDir.$file.'" selected="selected">'.$file.'</option>';
		}
		
		return $res.'</select>';
		
	}
	
	
	
 		function getTemplateSelector()	{
		
$this->content.='
<br/>
<table width="400" cellspacing="0" cellpadding="5" class="bgColor5" border="0" >
  <tr><tr>
    <td  class="bgColor2" colspan="3" align="center">Cairngorm codegeneration:</td>
  </tr>
 <td width="150">Make new Class</td>
    <td><input  type="input" size="50" name="className" value="[className]"  onfocus="this.value='."''".'"><input  type="hidden" name="pageid" value="'.$this->model->currentId.'"  ></td>
      <td>'.$this->getTemplates().'</td>
    </tr>
   <tr>
      <td>&nbsp;</td>
    <td  align="left">
    <input type="submit" onclick="document.location='."'index.php?id=".$this->model->currentId."&className='+document.editform.className.value+'&template='+document.editform.template.value".'; return false;" 
    name="view_" value="create class">
    </td>
  </tr>
</table><BR/>select template class first, fill in the new class name then save the results.';


	

							
	}
	
	
	
		
	
}	
 
?>
