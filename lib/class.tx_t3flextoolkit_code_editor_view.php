<?php

require_once($TYPO3_MOD_PATH.'../lib/model/class.tx_t3flextoolkit_model.php');
require_once($TYPO3_MOD_PATH.'../lib/utils/templating/class.tx_t3flextoolkit_dynamicToStaticCodeParser.php');
require_once($TYPO3_MOD_PATH.'../lib/utils/file/class.tx_t3flextoolkit_class_writer.php');


class tx_t3flextoolkit_code_editor_view {

	var $model;
	// Hold an instance of the class
	private static $instance;

	// A private constructor; prevents direct creation of object
	private function __construct() {

		if (!defined('TYPO3_MODE'))
			die('Access denied.');
		$this->model = tx_t3flextoolkit_model :: singleton();

	}

	// The singleton method
	public static function singleton() {
		if (!isset (self :: $instance)) {
			$c = __CLASS__;
			self :: $instance = new $c;
		}

		return self :: $instance;
	}

	function runEditor() {
//t3lib_div::debug($_GET);t3lib_div::debug($_POST);
if (t3lib_div::GPvar('staticCode')=='false') {

			$this->model->codeString = t3lib_div :: _GP('SpecialRequest');
			$this->saveCode();
			
			
			
		}
		
		$staticParser = t3lib_div::makeInstance('tx_t3flextoolkit_class_writer');
		$staticParser->init($this->model);
		$staticParser->makeStaticService();
	
 		
 		
		switch ($this->model->codeType) {
			case "actionscript" :
				return $this->parseEditAreaJs();
				break;
			case "mxml" :
				return $this->parseEditAreaMxml();
				break;
			case "php" :
				return $this->parseEditPhp();
				break;
		}
		

	}

	function saveCode() {

		if (!defined('TYPO3_MODE'))
			die('Access denied.');

		$flexform = '<?xml version="1.0" encoding="iso-8859-1" standalone="yes" ?>
								<T3FlexForms>
								    <data>
								        <sheet index="sDEF">
								            <language index="lDEF">
								                <field index="templatefiles">
								                    <value index="vDEF">' . $this->model->currentPageInfo['title'] . '</value>
								                </field>
								                <field index="editor">
								                    <value index="vDEF">' . htmlspecialchars($this->model->codeString) . '</value>
								                </field>
								            </language>
								        </sheet>
								        <sheet index="sDEF2">
								            <language index="lDEF">
								                <field index="comments">
								                    <value index="vDEF"></value>
								                </field>
								            </language>
								        </sheet>
								    </data>
								</T3FlexForms>';

		$tce = t3lib_div :: makeInstance('t3lib_TCEmain');

		$data['pages'][$this->model->currentId] = array (

			"title" => t3lib_div::GPvar('classNameChange'),
			"tx_flextoolkit_ds" => $flexform,
			"no_cache" => "1"
		);
		
		
		//t3lib_div::debug($data);
		$tce->stripslashes_values = 0;
		$tce->reverseOrder = 1;
		$tce->start($data, array ());
		$tce->process_datamap();
		$tce->process_cmdmap();
		t3lib_BEfunc :: getSetUpdateSignal('updatePageTree');

	}

	function parseFlashEditor() {

		$swfobjectJS = "../../../../typo3conf/ext/t3flextoolkit/mod1/swfobject/swfobject.js";
		$swfFilename = "../../../../typo3conf/ext/t3flextoolkit/as3Editor/bin-debug/Main.swf";
		$uid = $this->model->currentPageInfo['id'];
		$swfObjOutput =<<<HEREDOC
							<script type="text/javascript" src="$swfobjectJS"></script>
							
							
							<script type="text/javascript" >
							window.so$uid = new Object();
							</script>
							
							<div id="flashcontentrte$uid" style="width:1000px;height:600px;">		
								<strong>You need to upgrade your Flash Player</strong>
								This is replaced by the Flash content. 
								Place your alternate content here and users without the Flash plugin or with 
								Javascript turned off will see this. Content here allows you to leave out <code>noscript</code> 
								tags. Include a link to <a href="fullpage.html?detectflash=false">bypass the detection</a> if you wish.	
							</div>

							<script type="text/javascript">
								// <![CDATA[
								var so$uid = new SWFObject("$swfFilename", "rte$uid", "100%","100%", "9", "#EFEFF4");
								so$uid.addParam("scale", "scale");
								so$uid.addParam("allowFullScreen", "true");
								so$uid.addVariable("t3uid", "$uid");
								so$uid.addVariable("filePath", "$filename.as");
								so$uid.write("flashcontentrte$uid");	
								// ]]>
							</script>
							<script type="text/javascript">window.so$uid = document.forms[0].so$uid;</script>
							
							
HEREDOC;

		$code .= '<div class="buttongroup">
					<input type="image" onclick="document.editform.SpecialRequest.value=defid.getCode();document.editform._save.submit();" 
					name="save"  class="c-inputButton" src="../../../../typo3/sysext/t3skin/icons/gfx/savedok.gif" width="16" height="16" title="Save document" />
					
						</div><br /><TEXTAREA NAME="SpecialRequest"  
		   ROWS="3" COLS="25">' . t3lib_div :: formatForTextarea($this->model->codeString) . '
		</TEXTAREA> 
							<script type="text/javascript">
		
		document.editform.SpecialRequest.style.visibility="hidden";
		document.editform.SpecialRequest.style.position="absolute";
		document.editform.SpecialRequest.style.left="-1000";
		document.editform.SpecialRequest.style.overflow="hidden";
		</script>	
		
		
		<script type="text/javascript">
			
			
			
			
		function getContent(uid) {
			return document.editform.SpecialRequest.value;
		}
		function setContent(uid,val) {
			document.editform.SpecialRequest.value=""+val;
		}   
		function rescale() {
			
			//var d=document.getElementById("flashcontentrte' . $this->PA['uid'] . '");
			
			//document.getElementById("flashcontentrte' . $this->PA['uid'] . '").style.zIndex="100";
			//document.getElementById("flashcontentrte' . $this->PA['uid'] . '").style.position="absolute";
			//document.getElementById("flashcontentrte' . $this->PA['uid'] . '").style.top="0px";
			//document.getElementById("flashcontentrte' . $this->PA['uid'] . '").style.left="0px";
			//document.getElementById("flashcontentrte' . $this->PA['uid'] . '").style.width="100%";
			//document.getElementById("flashcontentrte' . $this->PA['uid'] . '").style.height="100%";
			
		
		
		}   
		
		
		
		
		</script>' . $swfObjOutput;

		return $code;
	}

function parseCodeMirror() {

	if (t3lib_div::GPvar('staticCode')=='true') {
			$templateParser = tx_t3flextoolkit_dynamicToStaticCodeParser::singleton();
			$code = t3lib_div :: formatForTextarea($templateParser->makeStatic($this->model->codeString));
	} else {
			$code = t3lib_div :: formatForTextarea($this->model->codeString);
	}

$swfObjOutput =<<<HEREDOC

<textarea id="code" name="SpecialRequest" cols="120" rows="30">$code</textarea>


HEREDOC;

		return $swfObjOutput;

		//.'GET:'.t3lib_div::view_array($_GET).'<br />'.'POST:'.t3lib_div::view_array($_POST).'<br />';

	}

	function parseCodeMirrorActionscript() {
if (t3lib_div::GPvar('staticCode')=='true') {
		$templateParser = tx_t3flextoolkit_dynamicToStaticCodeParser::singleton();

		$code = 'static: '.t3lib_div :: formatForTextarea($templateParser->makeStatic($this->model->codeString));
} else {
		$code = t3lib_div :: formatForTextarea($this->model->codeString);
}
		$swfObjOutput =<<<HEREDOC
<div class="buttongroup">
		
	<input type="hidden"  name="staticCode"/>
		<input class="c-inputButton" type="submit" onclick="document.editform.staticCode.value='false';document.editform._save.submit();" 
					name="save"  class="c-inputButton" src="../../../../typo3/sysext/t3skin/icons/gfx/savedok.gif" width="16" height="16" title="Save document" />
					<input class="c-inputButton" type="submit" onclick="document.editform.staticCode.value='true';document.editform._save.submit();"
					name="savbutton"  class="c-inputButton" src="../../../../typo3/sysext/t3skin/icons/gfx/savedok.gif" width="16" height="16" title="Save document" />
						
<div class="myborder">
<textarea id="code" name="SpecialRequest" cols="120" rows="30">$code</textarea>
</div>

HEREDOC;

		return $swfObjOutput;

		//.'GET:'.t3lib_div::view_array($_GET).'<br />'.'POST:'.t3lib_div::view_array($_POST).'<br />';

	}

function parseEditPhp() {

	if (t3lib_div::GPvar('staticCode')=='true') {
			$templateParser = tx_t3flextoolkit_dynamicToStaticCodeParser::singleton();
			$code = t3lib_div :: formatForTextarea($templateParser->makeStatic($this->model->codeString));
			
	} else {
			$code = t3lib_div :: formatForTextarea($this->model->codeString);
	}
$thepath=t3lib_div::getIndpEnv('TYPO3_SITE_URL');//.'typo3conf/ext/t3flextoolkit';
$id=$this->model->currentId;
$swfObjOutput =<<<HEREDOC

<SCRIPT LANGUAGE="JavaScript">
<!-- Begin
function popUp(URL) {
day = new Date();
id = day.getTime();
eval("page" + id + " = window.open(URL, 'sql', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=1,width=400,height=500');");
}
// End -->
</script>



<script language="javascript" type="text/javascript" src="../lib/view/edit_area/edit_area_full.js"></script>
<script language="javascript" type="text/javascript">
editAreaLoader.init({
	id : "textarea_1"		// textarea id
	,syntax: "php"			// syntax to be uses for highgliting
	,start_highlight: true		// to display with highlight mode on start-up
}); popUp('$thepath/typo3/alt_doc.php?db_new.php%3Fid%3D90&edit[tx_t3flextoolkit_query][$id]=new');
</script>

	<textarea id="textarea_1" style="border-width: 0px; overflow: hidden; display: inline; width: 100%; height: 800px;" name="SpecialRequest" cols="100" rows="30">$code</textarea>

HEREDOC;

		return $swfObjOutput;

		//.'GET:'.t3lib_div::view_array($_GET).'<br />'.'POST:'.t3lib_div::view_array($_POST).'<br />';

	}
function parseEditAreaMxml() {

	if (t3lib_div::GPvar('staticCode')=='true') {
			$templateParser = tx_t3flextoolkit_dynamicToStaticCodeParser::singleton();
			$code = t3lib_div :: formatForTextarea($templateParser->makeStatic($this->model->codeString));
	} else {
			$code = t3lib_div :: formatForTextarea($this->model->codeString);
	}

$swfObjOutput =<<<HEREDOC
<script language="javascript" type="text/javascript" src="../lib/view/edit_area/edit_area_full.js"></script>
<script language="javascript" type="text/javascript">
editAreaLoader.init({
	id : "textarea_1"		// textarea id
	,syntax: "xml"			// syntax to be uses for highgliting
	,start_highlight: true		// to display with highlight mode on start-up
	,show_line_colors: true
});
</script>

	<textarea id="textarea_1" style="border-width: 0px; overflow: hidden; display: inline; width: 100%; height: 800px;" name="SpecialRequest" cols="100" rows="30">$code</textarea>

HEREDOC;

		return $swfObjOutput;

		//.'GET:'.t3lib_div::view_array($_GET).'<br />'.'POST:'.t3lib_div::view_array($_POST).'<br />';

	}


function parseEditAreaJs() {

	if (t3lib_div::GPvar('staticCode')=='true') {
			$templateParser = tx_t3flextoolkit_dynamicToStaticCodeParser::singleton();
			$code = t3lib_div :: formatForTextarea($templateParser->makeStatic($this->model->codeString));
	} else {
			$code = t3lib_div :: formatForTextarea($this->model->codeString);
	}
//"search, go_to_line, |, undo, redo, |, select_font, |, syntax_selection, |, change_smooth_selection, highlight, reset_highlight, |, help"

$swfObjOutput =<<<HEREDOC
<script language="javascript" type="text/javascript" src="../lib/view/edit_area/edit_area_full.js"></script>
<script language="javascript" type="text/javascript">
editAreaLoader.init({
	id : "textarea_1"		// textarea id
	,syntax: "js"			// syntax to be uses for highgliting
	,start_highlight: true		// to display with highlight mode on start-up
	,toolbar: "autocompletion,search, go_to_line, |, undo, redo, |, select_font, |, syntax_selection, |, change_smooth_selection, highlight, reset_highlight, |, help"
	,syntax_selection_allow: "css,html,js,php,python,vb,xml,c,cpp,sql,basic,pas,brainfuck"
,show_line_colors: true
,autocompletion_start:true

});
</script>

	<textarea id="textarea_1" style="border-width: 0px; overflow: hidden; display: inline; width: 100%; height: 800px;" name="SpecialRequest" cols="100" rows="30">$code</textarea>

HEREDOC;

		return $swfObjOutput;

		//.'GET:'.t3lib_div::view_array($_GET).'<br />'.'POST:'.t3lib_div::view_array($_POST).'<br />';

	}

	function parseEditor() {

		return '
		
		<div class="buttongroup">
					<input type="submit" onclick="document.editform._save.submit();" 
					name="save"  class="c-inputButton" src="../../../../typo3/sysext/t3skin/icons/gfx/savedok.gif" width="16" height="16" title="Save document" />
					<input type="submit" onclick="document.editform._save.submit();" 
					name="staticCode"  class="c-inputButton" src="../../../../typo3/sysext/t3skin/icons/gfx/savedok.gif" width="16" height="16" title="Save document" />
					
						</div>
		
					
		<TEXTAREA NAME="SpecialRequest" style="position:absolute;left=-1000;visibility:hidden;overflow:hidden;" 
		   ROWS="3" COLS="25">
		</TEXTAREA>
		
		<textarea id="defid" style="width:95%;height:70%;"   wrap="virtual"  class="codepress ' . $this->model->codePressType . '" 
		name="mijneditor" value="test">' . $this->model->codeString . '</textarea>
		<script language="javascript" type="text/javascript">
		codeedit.edit(document.getElementById(' . "'defid'" . ').innerHTML,"html");
			</script>
		
			
		';

	}
}
?>
