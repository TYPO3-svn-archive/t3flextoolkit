<?php



require_once ($BACK_PATH."../typo3conf/ext/t3flextoolkit/lib/model/class.tx_t3flextoolkit_model.php");
class tx_t3flextoolkit_compiler_event {
 	
 	
 	
	var $model;
 	 	// Hold an instance of the class
    private static $instance;
    
    // A private constructor; prevents direct creation of object
    private function __construct() 
    {
        
     if (!defined ('TYPO3_MODE')) 	die ('Access denied.');		
					$this->model= tx_t3flextoolkit_model::singleton();	
      
    }

    // The singleton method
    public static function singleton() 
    {
        if (!isset(self::$instance)) {
            $c = __CLASS__;
            self::$instance = new $c;
        }

        return self::$instance;
    }
    
    // Example method
    public function bark()
    {
        echo 'Woof!';
    }

    // Prevent users to clone the instance
    public function __clone()
    {
        trigger_error('Clone is not allowed.', E_USER_ERROR);
    }
 	
 	
 	function createEmbedding() {
 		
$swfobjectJS= $this->model->realIPBaseUrl."typo3conf/ext/t3flextoolkit/mod1/swfobject/swfobject.js";
$swfFilename='page_'.$this->model->projectId.".swf";
$uid = $this->model->projectId;

$bgcolor=trim($this->model->projectProperties['data']['sDEF4']['lDEF']['bgcolor']['vDEF']);
$swfObjOutput = <<<HEREDOC
						<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
						<!-- $swfFilename -->
						<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
						<head>
						<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
						<title>::swf preview::</title>
						<script type="text/javascript" src="$swfobjectJS"></script>
						<style type="text/css">

							/* hide from ie on mac \*/
							html {
								height: 100%;
								overflow: hidden;
							}

							#flashcontent {
								height: 100%;
							}
							/* end hide */

							body {
								height: 100%;
								margin: 0;
								padding: 0;
								background-color: $bgcolor;
							}

						</style>

						</head>
						<body>

							<div id="flashcontent">		
								<strong>You need to upgrade your Flash Player</strong>
								This is replaced by the Flash content. 
								Place your alternate content here and users without the Flash plugin or with 
								Javascript turned off will see this. Content here allows you to leave out <code>noscript</code> 
								tags. Include a link to <a href="fullpage.html?detectflash=false">bypass the detection</a> if you wish.	
							</div>

							<script type="text/javascript">
								// <![CDATA[

								var so$uid = new SWFObject("$swfFilename", "$uid", "100%","100%", "9", "$bgcolor");
								so$uid.addParam("scale", "noscale");
								so$uid.addParam("salign","C");
								so$uid.addParam("currentPage","$uid");
								so$uid.write("flashcontent");	

								// ]]>
							</script>

						</body>
						</html>



HEREDOC;
	
			t3lib_div::fixPermissions($GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['absTypo3FileDocRoot'].$this->model->projectProperties['data']['sDEF']['lDEF']['deploydir']['vDEF'],0777);
			$htmlfile=$GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['absTypo3FileDocRoot'].$this->model->projectProperties['data']['sDEF']['lDEF']['deploydir']['vDEF']."index_".$_GET['id'].".html";
			t3lib_div::writeFile(''.$htmlfile,$swfObjOutput);
			$htmlfile=$GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['absTypo3FileDocRoot'].$this->model->projectProperties['data']['sDEF']['lDEF']['deploydir']['vDEF']."index.html";
			t3lib_div::writeFile($htmlfile,$swfObjOutput);			

 	}
 	
 	function makeAntBuildFile() { 
 		
 		
 
 							$this->createEmbedding();
 							
 						
 							if (!defined ('TYPO3_MODE') && (!TYPO3_MODE=="BE")) 	die ('Access denied.'); 
 							$_SESSION['bussy'] =1;
							$mxmlc=trim($this->model->projectProperties['data']['sDEF']['lDEF']['defaultInput']['vDEF']);
							
							
							
							
							
							$targetId=$this->model->projectProperties['data']['sDEF3']['lDEF']['classpath']['vDEF'];
							
							
							$target='';
							$this->targetRecord = t3lib_BEfunc::getRecord('pages', $targetId,'*');
							if($this->targetRecord['doktype']==$GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['registertodoktypesMXML']) {
								$target = $this->targetRecord['title'].'.mxml';
							} else {
								$target = $this->targetRecord['title'].'.as';
							}
 							
 							
 							
 						//	t3lib_div::debug($this->targetRecord );
 	
							$framerate=trim($this->model->projectProperties['data']['sDEF4']['lDEF']['framerate']['vDEF']);
						
							$uid= $this->model->projectId;
							
							$bgcolor=trim($this->model->projectProperties['data']['sDEF4']['lDEF']['bgcolor']['vDEF']);
							$width=trim($this->model->projectProperties['data']['sDEF4']['lDEF']['width']['vDEF']);
							$height=trim($this->model->projectProperties['data']['sDEF4']['lDEF']['height']['vDEF']);
							$deploydir=trim($this->model->projectProperties['data']['sDEF']['lDEF']['deploydir']['vDEF']);
							if (!is_dir($deploydir)) {
								//maak dir
								$baseDir = trim($GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['absTypo3FileDocRoot']);

								t3lib_div::mkdir_deep($baseDir."/",$deploydir."/");
						
							}



		

							$antbasedir = trim($GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['absTypo3FileDocRoot']);
							$options = trim($this->model->projectProperties['data']['sDEF']['lDEF']['coptions']['vDEF']);
							
							$this->thetime = date("dHis");	
							$fulldeploydir=trim($antbasedir.$deploydir.'page_'.$this->model->projectId.'.swf');
							
							$sourcepath=str_replace(" ","_",$this->model->relFlexfilesDir.$this->model->projectStartingRecord['title'].'/' );
							$fulltargetdir=trim($antbasedir.$sourcepath.$target);
							
							$this->fulldeploydir=trim($fulldeploydir);
						
							
							$filename = PATH_typo3conf."../fileadmin/flexfiles/build_local_".$this->model->projectId.".xml";

							if (file_exists($filename)) {
								unlink($filename);
								$output .= $filename."overwriting build_local_".$this->model->projectId.".xml <br/>";
						
							} else {
								$output .= "writing build_local_".$this->model->projectId.".xml <br/>";
							}



if ($this->model->projectProperties['data']['sDEF8']['lDEF']['templateFile']['vDEF']) {
$libIncludes=split(",",$this->model->projectProperties['data']['sDEF8']['lDEF']['templateFile']['vDEF']);
$rootdir= t3lib_div::resolveBackPath(PATH_typo3.'../');
$argLibLine = '<arg line="-include-libraries  ';

foreach($libIncludes as $lib) {
	$argLibLine .=$rootdir."fileadmin/flexfiles/lib/".$lib." ";
}			
//t3lib_div::debug($argLibLine);
$argLibLine .= '" />';

} else {
	t3lib_div::debug("NO LIBS");
	
}


$antOutput = <<<HEREDOC
<?xml version="1.0"?>				
<project name="compile" default="compile" basedir="$antbasedir">

<target name="compile">				
			<exec executable="$mxmlc">	
			<arg line="-source-path=$sourcepath" />
			<arg line="-debug=false" />
			$argLibLine
			<arg line="-warnings=true"	/>		
			<arg line="-default-frame-rate=$framerate" />
			<arg line="-default-size $width $height" />			
			<arg line="-default-background-color=$bgcolor" />
			<arg line="$fulltargetdir"/>	
			<arg line="-o=$fulldeploydir"/>
			<arg line="$options"/>
	  	 	</exec>
	  	 	</target>
</project>


HEREDOC;

				
				t3lib_div::writeFile($filename,$antOutput);
				//t3lib_div::debug($antOutput);
				
				
				$this->model->compilerEvent=1;
				return $this->compile();
 			}
 			
 			
	function compile() {
 				if (!defined ('TYPO3_MODE') && (!TYPO3_MODE=="BE")) 	die ('Access denied.'); 
 				$this->model->compilerEvent=1;
 				$this->model->compilerEvent=1;
				$logoutput .= $this->execInBackground("ant -f ".trim($GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['absTypo3FileDocRoot'])."fileadmin/flexfiles/build_local_".$this->model->projectId.".xml");
				
				return $this->decodeLog($logoutput);
 			}
 			
 	function decodeLog($log) {
 		
 		

 							$subject = $log;
							$pattern = '/(FAILED)/';
							$matches=array();
							
						
 				
						if(preg_match($pattern, $subject, $matches)) {
 							//	$this->model->compilerView=5;
 							}

 					
 					return $log;
 			}
 			
 	function execInBackground($exe, $args = "") {
			 
			if (!defined ('TYPO3_MODE') && (!TYPO3_MODE=="BE")) 	die ('Access denied.'); 
			$output=array();
			$command = "$exe $args 2>&1";
			exec($command, $output);
			 
	
			$myoutput = "";
			foreach($output as $outputline) {
				$myoutput .= "$outputline <br>";
			}
			return $myoutput;
			}		
}	
 
?>
