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
		
		
		$tce->stripslashes_values = 0;
		$tce->reverseOrder = 1;
		$tce->start($data, array ());
		$tce->process_datamap();
		$tce->process_cmdmap();
		t3lib_BEfunc :: getSetUpdateSignal('updatePageTree');

	}



function parseEditPhp() {

	if (t3lib_div::GPvar('staticCode')=='true') {
			$templateParser = tx_t3flextoolkit_dynamicToStaticCodeParser::singleton();
			$code = t3lib_div :: formatForTextarea($templateParser->makeStatic($this->model->codeString));
			
	} else {
			$code = t3lib_div :: formatForTextarea($this->model->codeString);
	}
$thepath=t3lib_div::getIndpEnv('TYPO3_SITE_URL');
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
}); 
</script>

	<textarea id="textarea_1" style="border-width: 0px; overflow: hidden; display: inline; width: 100%; height: 800px;" name="SpecialRequest" cols="100" rows="30">$code</textarea>

HEREDOC;

		return $swfObjOutput;

	

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

	
	}


function parseEditAreaJs() {

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
	,syntax: "js"			// syntax to be uses for highgliting
	,start_highlight: true		// to display with highlight mode on start-up
	,toolbar: "autocompletion,search, go_to_line, |, undo, redo, |, select_font, |, syntax_selection, |, change_smooth_selection, highlight, reset_highlight, |, help"
	,syntax_selection_allow: "css,html,js,php,python,vb,xml,c,cpp,sql,basic,pas,brainfuck"
,show_line_colors: true
,autocompletion_start:true

});
</script>

	<textarea id="textarea_1" style="border-width: 0px; overflow: hidden; display: inline; width: 100%; height:600px;" name="SpecialRequest" cols="100" rows="30">$code</textarea>

HEREDOC;

		return $swfObjOutput;

		

	}

	
}
?>
