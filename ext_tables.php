<?php

if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

if (TYPO3_MODE=="BE")	{
	
require_once(t3lib_extMgm::extPath($_EXTKEY)."class.tx_project_flexform.php"); 



if ($GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['showInModuleList']==1) {
	    $module_naam = 'txt3flextoolkittab'; // name of main backend module tab
	    $place_after = 'web'; // web, file, doc, tools, help
   
t3lib_extMgm::addModule($module_naam,"","top",t3lib_extMgm::extPath($_EXTKEY));

// adding Compiler module
t3lib_extMgm::addModule('txt3flextoolkittab','txt3flextoolkitM1','',t3lib_extMgm::extPath($_EXTKEY).'mod1/');
// adding Editor module
t3lib_extMgm::addModule('txt3flextoolkittab','txt3flextoolkitM2','',t3lib_extMgm::extPath($_EXTKEY).'mod2/');
// adding generator module
t3lib_extMgm::addModule('txt3flextoolkittab','txt3flextoolkitM3','',t3lib_extMgm::extPath($_EXTKEY).'mod3/');


t3lib_extMgm::addLLrefForTCAdescr('tx_t3flextool_mod2','EXT:t3flextoolkit/lang/locallang_csh.php');

    $temp_BE_MODULES = array();
    foreach ($TBE_MODULES as $key => $val) {
        $temp_BE_MODULES[$key] = $val;
        if ($key == $place_after) {
            $temp_BE_MODULES[$module_naam] = $val;
        }
}
$TBE_MODULES = $temp_BE_MODULES;
}
// add context menu for filesytem/ t3 CMS filelist module
	$GLOBALS["TBE_MODULES_EXT"]["xMOD_alt_clickmenu"]["extendCMclasses"][]=array(
	"name" => "tx_t3flextoolkit_cm1",
	"path" => t3lib_extMgm::extPath($_EXTKEY)."class.tx_t3flextoolkit_cm1.php");
	

// add context menu for filesytem/ t3 CMS filelist module
//	$GLOBALS["TBE_MODULES_EXT"]["xMOD_alt_clickmenu"]["extendCMclasses"][]=array(
//	"name" => "tx_t3flextoolkit_cm1",
//	"path" => t3lib_extMgm::extPath($_EXTKEY)."class.tx_t3flextoolkit_cm1.php");
	
	

	// to do --> auto media record creation after compiling....
	t3lib_div::loadTCA('tt_content');


// Dynamic flexform  at page table DS (typo3 flexform not flash) for custom settings (default settings at info tab of the extension manager);
// todo: Make this multi Language...

// setting custom new PAGETYPE 
// TODO: how can icons be integrated?
$tempColumns = Array (
	'tx_flextoolkit_ds' => Array (		
		'exclude' => 1,		
		'label' => 'flexform',	
		

		'config' => Array (
		
			'type' => 'flex',
			'ds_pointerField' => 'doktype',
			'ds' => Array(
				'default' => 'FILE:EXT:t3flextoolkit/flexforms/flexformsflexform_ds.xml',	
				''.$GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['registertodoktypesPROJECT'] => tx_project_flexform::getXml(),
				''.$GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['registertodoktypesAMF'] =>'FILE:EXT:t3flextoolkit/flexforms/flexform_service_ds.xml',
				''.$GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['registertodoktypesACTIONSCRIPT'] => 'FILE:EXT:t3flextoolkit/flexforms/flexform_actionscript_ds.xml',
				''.$GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['registertodoktypesMXML'] => 'FILE:EXT:t3flextoolkit/flexforms/flexform_mxml_ds.xml',	
				''.$GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['registertodoktypesCSS'] => 'FILE:EXT:t3flextoolkit/flexforms/flexform_css_ds.xml',
				''.$GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['registertodoktypesTEMPLATE'] => 'FILE:EXT:t3flextoolkit/flexforms/flexform_element_ds.xml',	
			),
		),
	),
);


		
		

    


// Adding pages_types:
// TODO: manage icons in a more centralized way 
// t3lib_div::array_merge() MUST be used!
$PAGES_TYPES = t3lib_div::array_merge(array(
		''.$GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['registertodoktypesPROJECT'] => Array(	
				'icon' =>'http://'.t3lib_div::getThisUrl().'../typo3conf/ext/t3flextoolkit/icons/flexicon.gif', 
				'allowedTables' => '*'
		),
		''.$GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['registertodoktypesAMF'] => Array(	
				'icon' =>'http://'.t3lib_div::getThisUrl().'../typo3conf/ext/t3flextoolkit/icons/flexicon_amf.png',
				'allowedTables' => '*'
		),
		''.$GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['registertodoktypesCAIRNGORM'] => Array(	
				'icon' =>'http://'.t3lib_div::getThisUrl().'../typo3conf/ext/t3flextoolkit/icons/flexicon_cai.png',
				'allowedTables' => '*'

		),
		''.$GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['registertodoktypesMXML'] => Array(	
				'icon' =>'http://'.t3lib_div::getThisUrl().'../typo3conf/ext/t3flextoolkit/icons/C_FlexApplicationView_md.png', 
				'allowedTables' => '*'
		),
		''.$GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['registertodoktypesCSS'] => Array(	
				'icon' =>'http://'.t3lib_div::getThisUrl().'../typo3conf/ext/t3flextoolkit/icons/C_CSSFile_md.png', 
				'allowedTables' => '*'
		),
		''.$GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['registertodoktypesACTIONSCRIPT'] => Array(	
				'icon' =>'http://'.t3lib_div::getThisUrl().'../typo3conf/ext/t3flextoolkit/icons/C_ActionScriptFile_md.png', 
				'allowedTables' => '*'
		),	
	''.$GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['registertodoktypesTEMPLATE'] => Array(	
				'icon' =>'http://'.t3lib_div::getThisUrl().'../typo3conf/ext/t3flextoolkit/icons/C_ActionScriptFile_edit_md.png', 
				'allowedTables' => '*'
		),	
	),$PAGES_TYPES);


// new icons for custom MXML (mxml) and AS (actionscript) file ext	
$FILEICONS= t3lib_div::array_merge($FILEICONS,Array(	'mxml' => '../../../typo3conf/ext/t3flextoolkit/icons/C_FlexApplicationView_md.png' ));	
$FILEICONS= t3lib_div::array_merge($FILEICONS,Array(	'as' => '../../../typo3conf/ext/t3flextoolkit/icons/C_ActionScriptFile_md.png' ));


t3lib_div::loadTCA('tt_content');
$TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY.'_pi2']='layout,select_key,pages,recursive';
$TCA['tt_content']['types']['list']['subtypes_addlist'][$_EXTKEY.'_pi2']='pi_flexform';
t3lib_extMgm::addPiFlexFormValue($_EXTKEY.'_pi2', 'FILE:EXT:t3flextoolkit/flexforms/flexform_service_generator_ds.xml');
t3lib_extMgm::addPlugin(Array('LLL:EXT:t3flextoolkit/locallang_db.xml:tt_content.list_type_pi1', $_EXTKEY.'_pi2'),'list_type');
t3lib_extMgm::addStaticFile($_EXTKEY,'pi2/static/', '[t3flextoolkit] default template');


t3lib_extMgm::addPlugin(Array('amfphp service', $_EXTKEY.'_pi1'),'CType');

t3lib_div::loadTCA("pages");

// Add allowed records to pages:
t3lib_extMgm::allowTableOnStandardPages('pages_language_overlay,tt_content,sys_template,sys_domain');

// Merging in CMS doktypes:
	array_splice(
		$TCA['pages']['columns']['doktype']['config']['items'],
		8,
		0,
		Array(
		Array('t3flex project',''.$GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['registertodoktypesPROJECT'],'../typo3conf/ext/t3flextoolkit/icons/flexicon.gif'),
		Array('AMFPHP Class', ''.$GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['registertodoktypesAMF'],'../typo3conf/ext/t3flextoolkit/icons/flexicon_amf.png'),
		Array('ActionScript3 Class', ''.$GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['registertodoktypesACTIONSCRIPT'],'../typo3conf/ext/t3flextoolkit/icons/C_ActionScriptFile_md.png'),
		Array('CSS Class', ''.$GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['registertodoktypesCSS'],'../typo3conf/ext/t3flextoolkit/icons/C_CSSFile_md.png'),
		Array('MXML Class',''.$GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['registertodoktypesMXML'],'../typo3conf/ext/t3flextoolkit/icons/C_FlexApplicationView_md.png'),
		Array('Class template', ''.$GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['registertodoktypesTEMPLATE'],'../typo3conf/ext/t3flextoolkit/icons/C_ActionScriptFile_edit_md.png'),
		)
);





	//	t3lib_div::debug($TCA['pages']['columns']['doktype']['config']['items']);



t3lib_extMgm::addTCAcolumns("pages",$tempColumns,1);

//  TCA for FLEX application
$GLOBALS['TCA']['pages']['types'][''.$GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['registertodoktypesPROJECT']]['showitem']= 'doktype,title;class name,hidden,tx_flextoolkit_ds,TSconfig;CONFIG MARKERS (PAGETS)';


//  TCA for amf service
$GLOBALS['TCA']['pages']['types'][''.$GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['registertodoktypes']]['showitem']= 'doktype,title;project name,hidden,tx_flextoolkit_ds';

$GLOBALS['TCA']['pages']['types'][''.$GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['registertodoktypesAMF']]['showitem']= 'doktype,title;class name,hidden,tx_flextoolkit_ds';
//$GLOBALS['TCA']['pages']['types'][''.$GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['registertodoktypesACTIONSCRIPT']]['showitem']= 'doktype,title;class name,hidden,tx_flextoolkit_ds';
//$GLOBALS['TCA']['pages']['types'][''.$GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['registertodoktypesMXML']]['showitem']= 'doktype,title;class name,hidden,tx_flextoolkit_ds';
//$GLOBALS['TCA']['pages']['types'][''.$GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['registertodoktypesCSS']]['showitem']= 'doktype,title;class name,hidden,tx_flextoolkit_ds';
//$GLOBALS['TCA']['pages']['types'][''.$GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['registertodoktypesTEMPLATE']]['showitem']= 'doktype,title;class name,hidden,tx_flextoolkit_ds';
//t3lib_div::debug($GLOBALS['TCA']['pages']);

	
	
}




?>


?>