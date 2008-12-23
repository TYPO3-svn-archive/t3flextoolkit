<?php
/*
 * Created on 2 okt 2008
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 
class tx_t3flextoolkit_model {
 	
 	var $myGlobal='TEST OK';
 	var $projectId;
 	var $currentId;
 	var $projectProperties;
 	
 	var $projectStartingRecord;
 	var $projectFlexForm;
 	
 	var $compilerEvent=0;
 	
 	var $isFlexCode=true;
 	var $currFlexform;
 	var $codeType;
 	var $codeString;
 	var $codeExt;
 	
 	var $classname;
 	var $package;
 	
 	var $realIP;
	var $realIPBaseUrl;
 	var $comName;
 	var $domainName;
 	
 	var $currentPageInfo;
 	var $currentFlexFormInfoAsArray;
 	var $debug=false;
 	var $status=true;
 	
 	var $JScode;
 	var $extRelPath;
 	var $extPath;
 	var $extentionHttpRoot;
 	
 	var $relFlexfilesDir;
 	var $flexfilesDir;
 	var $deployDir;
 	var $srcDir;
	
	var $mycounter=0;

	var $compilerBussy=0;
	var $compilerView=1;
	var $cairngormCreatorView=1;
	

 	var $ProjectStatus; 
 	
 	
 	// Hold an instance of the class
    private static $instance;
    
    // A private constructor; prevents direct creation of object
    private function __construct() 
    {
        
     //   echo 'I am constructed';
      $this->mycounter++;
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
 	
 	
 	function init($id) {
 		$this->currentId=$id;
 		$this->setExtProps();
 	}
 	
 	function setExtProps() {
 		$this->getCurrentIdInfo();
 		
 		if ($this->codeType=='noflex') {} else {
		$this->extRelPath = t3lib_extMgm::extRelPath('t3flextoolkit');
 		$this->extPath = t3lib_extMgm::extPath('t3flextoolkit');
 		$this->projectId=$this->getProjectId();
 		$this->projectProperties =$this->getProjectSettings();
 		$this->realIP=$this->getRealIpAddr();
 		$this->realIPBaseUrl=str_replace('localhost',$this->realIP,t3lib_div::getIndpEnv('TYPO3_SITE_URL'));
 		$this->domainName = $this->projectProperties['data']['sDEF6']['lDEF']['domainName']['vDEF'];
 		$this->comName = $this->projectProperties['data']['sDEF6']['lDEF']['comName']['vDEF'];
 $this->relFlexfilesDir='fileadmin/flexfiles/projectsource/';
 		
 		if ($this->debug==true) {
			t3lib_div::debug($this->extentionAbsFileRoot);
			
 		}	
 		}
 	}
 	
 	function updateProjectSettings($id) {
 		
 	}
 	
	 	
 	function getProjectId() {
 		$rootLine = t3lib_BEfunc::BEgetRootLine($this->currentId);	
 		return $rootLine[1]['uid'];
 	}
 	
 	
 	function getRealIpAddr()

{
  if (!empty($_SERVER['HTTP_CLIENT_IP']))
  //check ip from share internet
  {
    $ip=$_SERVER['HTTP_CLIENT_IP'];
  }
  elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
  //to check ip is pass from proxy
  {
    $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
  }
  else
  {
    $ip=$_SERVER['REMOTE_ADDR'];
  }
  return $ip;
}
 	
 	
 	function getCurrentIdInfo() {
		$this->currentPageInfo = t3lib_BEfunc::getRecord('pages', $this->currentId,'*');	
		$this->isFlexCode=true;

		switch ($this->currentPageInfo['doktype']) {
				
				default:
					$this->codeType='noflex';
					$this->codeExt='';
					$this->isFlexCode=false;
				break;
				case $GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['registertodoktypesPROJECT']:
					$this->codeType='project';
					$this->codeExt='flex';
					$this->codePressType='flex';
					
				break;
				case $GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['registertodoktypesACTIONSCRIPT']:
					$this->codeType='actionscript';
					$this->codeExt='as';
					$this->codePressType='actionscript3';
					
				break;
				case $GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['registertodoktypesMXML']:
					$this->codeType='mxml';
					$this->codeExt='mxml';
					$this->codePressType='html';
				break;
				case $GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['registertodoktypesCSS']:
					$this->codeType='css';
					$this->codeExt='css';
					$this->codePressType='css';
				break;
				case $GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['registertodoktypesAMF']:
					$this->codeType='php';
					$this->codeExt='php';
					$this->codePressType='php';
				break;
				case $GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['registertodoktypesTEMPLATE']:
					$this->codeType='php';
					$this->codeExt='php';				
					$this->codePressType='php';
				break;
				
		}
		
		
		
		if ($this->isFlexCode==true) {
		
		$this->currFlexform = $this->currentPageInfo['tx_flextoolkit_ds'];		
		$tmp = t3lib_div :: resolveAllSheetsInDS(array($this->currFlexform));
		if ($tmp['sheets']['sDEF']['0']=='') {
			$this->codeString = 'ERROR IN FLEXFORM: no flexform';		
		} else {
		
		$this->currentFlexFormInfoAsArray = t3lib_div :: xml2array($tmp['sheets']['sDEF']['0']);		
		
		$checkForInvalidEnd= split('Invalid document end',$this->currentFlexFormInfoAsArray);
		$checkForEmpty = split('Empty document',$this->currentFlexFormInfoAsArray);
		
		if (count($checkForInvalidEnd) > 1 || count($checkForEmpty) > 1) {
			$this->codeString = 'ERROR IN FLEXFORM: '.$this->currentFlexFormInfoAsArray;
			$this->status = false;
		} else {			
				
				$this->codeString=$this->currentFlexFormInfoAsArray['data']['sDEF']['lDEF']['editor']['vDEF'];
		}
		
		}
		}
		
 	}
 
 
 	function getProjectSettings() {
		$this->projectStartingRecord = t3lib_BEfunc::getRecord('pages', $this->projectId,'*');
		$this->projectFlexForm = $this->projectStartingRecord['tx_flextoolkit_ds'];
		$tmp = t3lib_div :: resolveAllSheetsInDS(array($this->projectStartingRecord));
 		$tmp = t3lib_div :: xml2array($tmp['sheets']['sDEF']['0']['tx_flextoolkit_ds']);
 		
 		return $tmp;
 	}
 	
 	function setProjectSettings($pageinfo) {
 	
 		$this->CurrentPageInfo=$pageinfo;
 		if ($this->debug==true) {
			t3lib_div::debug($pageinfo);
 		}	
	}
 	
 	
 	
 }
 
?>
