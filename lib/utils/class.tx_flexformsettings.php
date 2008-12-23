<?php
/*
*
*
*				
*/ //style="z-index:100;position:relative;left:-30px;top:-100px;width:1000px;height:600px;"

class tx_flexformsettings {

function sourcepath($config) {

$optionList = array();
    // add first option
    
    
    
    
    $optionList[0] = array(0 => 'fileadmin/flexfiles/projectsource/'.str_replace(" ","_",$config['row']['title']).'/', 1 => 'fileadmin/flexfiles/projectsource/'.str_replace(" ","_",$config['row']['title']).'/');
  
    $config['items'] = array_merge($config['items'],$optionList);
    return $config;
  }
function targetClass($config) {

$optionList = array();
      
    $optionList[0] = array(0 => 'fileadmin/flexfiles/projectsource/'.str_replace(" ","_",$config['row']['title']), 1 => 'fileadmin/flexfiles/projectsource/'.str_replace(" ","_",$config['row']['title']));
   
    $config['items'] = array_merge($config['items'],$optionList);
    return $config;
  }


function compilerPath($config) {

$optionList = array();
      
    $optionList[0] = array(0 => $GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['MXMLC'], 1 => $GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['MXMLC']);
   
    $config['items'] = array_merge($config['items'],$optionList);
    return $config;
  }



 }

?>