<?php
/*
 * Created on 2 okt 2008
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 
class tx_t3flextoolkit_dynamicToStaticCodeParser {

 	var $dynamicCode;
 
 
	function setDynamicCode($dynamCode) {
 		$this->dynamicCode=$dynamCode;
 	}
 	

 	
	function makeStatic($dynamicCode)
	{	
		 $searchPattern          = '/###([a-zA-Z0-9_:\/\.]*)###/i'; //macro delimiter "{" and "}"
     	 $replacementFunction    = array(&$this, 'parseMatchedText');  //Method callbacks are performed this way       
     	 $parsedTemplate         = preg_replace_callback($searchPattern, $replacementFunction, $dynamicCode);
     
     	return $parsedTemplate; 
		
	}
	
	
	
	/**
   * Callback function that returns value of a matching macro
   * @param Array $matches
   * @return String value of matching macro
   */
   function parseMatchedText($matches)
   {
      	 return $this->parse($matches);
   } 
   
   
   
 	// Hold an instance of the class
    private static $instance;
    
    // A private constructor; prevents direct creation of object
    private function __construct() 
    {
        	$this->model = tx_t3flextoolkit_model :: singleton();
     
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
    

    // Prevent users to clone the instance
    public function __clone()
    {
        trigger_error('Clone is not allowed.', E_USER_ERROR);
    }
 	
function tt_content($key) {
			$res =  $GLOBALS["TYPO3_DB"]->sql_query("SELECT bodytext FROM tt_content WHERE header='".$key."'");
			$row = mysql_fetch_row($res);		
		//	t3lib_div::debug($row[0]);	
		return  $row[0];
		}


	function tt_contentImage($key) {
			$res =  $GLOBALS["TYPO3_DB"]->sql_query("SELECT image FROM tt_content WHERE select_key='".$key."'");
			$row = mysql_fetch_row($res);		
			//t3lib_div::debug($row[0]);	
		return  $row[0];
		}	
		
		
		function tt_contentImage_embed($key) {
			$res =  $GLOBALS["TYPO3_DB"]->sql_query("SELECT image FROM tt_content WHERE select_key='".$key."'");
			$row = mysql_fetch_row($res);		
			//t3lib_div::debug($row[0]);	
		return  $row[0];
		}	
		
		
	function replace_backslash($str_to_detect, $str_replace) {
		if ($this->chrs_is_all_readable($str_to_detect)) {
			return (preg_replace('[\/]', $str_replace, $str_to_detect));
		}
	}
	
	function chrs_is_all_readable($string) {
		for ($i = 0; $i < strlen($string); $i++) {
			$chr = $string {
				$i };
			$ord = ord($chr);
			if ($ord < 32 or $ord > 126)
				return (false);
		}
		return (true);
	}		
function anyTable($table,$field,$key,$value) {

$res =  $GLOBALS["TYPO3_DB"]->sql_query("SELECT ".$field." FROM ".$table." WHERE ".$key."='".$value."'");
			$row = mysql_fetch_row($res);		
			//	t3lib_div::debug($row);	
			return  $row[0];
		}



 	public function parse($matches){
 		$returnval=""; 		
 		$t3ArrKeys = explode(":", $matches[1]);
 		
 		//t3lib_div::debug($t3ArrKeys);
		switch ($t3ArrKeys[0]) {
			case "SETUP" :
				$returnval = $GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup'][$t3ArrKeys[1]];
				break;
			case "SELF" :
				$returnval = $this->model->currentPageInfo[$t3ArrKeys[1]];
				break;
			case "CLASSPATH" :
				$returnval = $this->replace_backslash(t3lib_befunc :: getRecordPath($this->model->currentPageInfo['uid'], '', '', 0), ".");
				$returnval = substr($returnval, 0, -1); // returns "d"
				$returnval = substr($returnval, 1, strlen($returnval));
				break;	
			case "PACKAGE" :
				$returnval = $this->replace_backslash(t3lib_befunc :: getRecordPath($this->model->currentPageInfo['uid'], '', '', 0), ".");
				$returnval = substr($returnval, 0, -1); // returns "d"
				$returnval = substr($returnval, 1, strlen($returnval));
				$chunks= explode('.',$returnval);
				$returnval = str_replace($chunks[0].'.','',$returnval);
				$returnval = str_replace('.'.$chunks[sizeof($chunks)-1],'',$returnval);
				break;
			case "SRCDIR" :
				$returnval = $this->replace_backslash(t3lib_befunc :: getRecordPath($this->model->currentPageInfo['uid'], '', '', 0), ".");
				$returnval = substr($returnval, 0, -1); // returns "d"
				$returnval = substr($returnval, 1, strlen($returnval));
				$chunks= explode('.',$returnval);
				$returnval = $chunks[0];
				break;
				
					
			case "VIEW" :
				$returnval =$this->model->comName.'.'. $this->model->domainName.'.view';
				break;
			case "BUSINESS" :
				$returnval =$this->model->comName.'.'. $this->model->domainName.'.business';
				break;
			case "CONTROL" :			
				$returnval =$this->model->comName.'.'. $this->model->domainName.'.control';
				break;
			case "MODEL" :	
				$returnval = $this->model->comName.'.'. $this->model->domainName.'.model';
				break;
			case "COMMAND" :	
				$returnval =$this->model->comName.'.'. $this->model->domainName.'.command';
				break;
			case "VO" :	
				$returnval =$this->model->comName.'.'. $this->model->domainName.'.vo';
				break;
			case "LICENSE" :	
				$returnval = $this->AppFlexFormTreeDat['data']['sDEF7']['lDEF']['License']['vDEF'];
				break;
			case "COMMAND_CONSTANTS_STATICS" :
				$returnval='';
				require_once($TYPO3_MOD_PATH.'../lib/utils/treeiterator/class.tx_t3flextoolkit_project_tree_util.php'); 
				$tree=tx_t3flextoolkit_project_tree_util::GetTreeArrayFromCustomId($this->model->currentPageInfo['pid']);
				//
				      foreach($tree as $command)    {
						if($command['row']['uid']==$this->model->currentPageInfo['uid'] ||  $command['row']['hidden']==1 || $command['row']['title']=='control'){ 
						
						} else {
						$returnval.='public static const '.strtoupper (str_replace('Command','EVENT',$command['row']['title'])).' : String = "'.strtoupper ($command['row']['title']).'";						
		';
		}
          					  }
          					  
			break;
			case "COMMAND_CONSTANTS" :
				
				$returnval='';
				require_once($TYPO3_MOD_PATH.'../lib/utils/treeiterator/class.tx_t3flextoolkit_project_tree_util.php'); 
				$tree=tx_t3flextoolkit_project_tree_util::GetTreeArrayFromCustomId($this->model->currentPageInfo['pid']);
				//
				      foreach($tree as $command)    {
						if($command['row']['uid']==$this->model->currentPageInfo['uid'] ||  $command['row']['hidden']==1 || $command['row']['title']=='control'){ 
						
						} else {
						$returnval.='addCommand( AppController.'.strtoupper (str_replace('Command','EVENT',$command['row']['title'])).' ,'.str_replace("Event","Command",$command['row']['title']).');
		';
          					}  }
          					  
			break;
			case "CLASSFILENAME" :
				$returnval = $this->model->currentPageInfo['title'];
			case "CLASS" :
				$returnval = $this->model->currentPageInfo['title'];
				break;
			case "CLASSFILENAMECAPS" :
				$returnval =strtoupper($this->model->currentPageInfo['title']);
			
				break;
			case "ENV" :
				$returnval = t3lib_div :: getIndpEnv($t3ArrKeys[1]);
				break;
			case "GETFILECONTENT" :
				$returnval = t3lib_div::getURL($t3ArrKeys[1]); 
				break;
			case "GETURLCONTENT":
				$returnval = t3lib_div::getURL('http://'.$t3ArrKeys[1]); 
				break;
			case "DATE" :
				$returnval =date(DATE_RFC822);
				break;
			case "CSS" :
				$returnval =  trim($GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['absTypo3FileDocRoot']).'fileadmin/flexFiles/src' . dirname(t3lib_befunc :: getRecordPath($this->model->currentPageInfo['uid'], '', '', 0)) . '/' . $t3ArrKeys[1] . '.css';
				break;
			case "HOME" :
				$returnval = t3lib_div :: getIndpEnv('TYPO3_SITE_URL');
				break;
			case "USER" :
				$returnval = $GLOBALS['BE_USER']->user[$t3ArrKeys[1]];
			case "PAGETS" :
			
				//$tsvars=
				//t3lib_div::debug($tsvars['flexVars.']);
				// = $GLOBALS['BE_USER']->user[$t3ArrKeys[1]];
				
				// Get complete TS template
				$tmpl = t3lib_BEfunc::getPagesTSconfig($this->model->currentId,$rootLine='',$returnPartArray=0);
				
				// Get TS object hierarchy in template
				$tmplPath = explode('.',$t3ArrKeys[1]);
				
				
				$tsObj = $tmpl;
				
				// Process TS object hierarchy
				for($i = 0; $i < count($tmplPath)-1; $i++) {
					
					$tsObj = $tsObj[$tmplPath[$i] . '.'];
					
				}
				
				//t3lib_div::debug($tsObj[$tmplPath[count($tmplPath)-1]]);
			
				$returnval = $tsObj[$tmplPath[count($tmplPath)-1]];

				
				break;
			case "LOCATION" :
				$returnval = t3lib_div :: getIndpEnv('TYPO3_SITE_URL') . 'fileadmin/flexFiles/as' . dirname(t3lib_befunc :: getRecordPath($this->model->currentPageInfo['uid'], '', '', 0)) . '/' .$this->model->currentPageInfo['title'] . '.as';
				break;
			case "CONTENT" :
				$returnval =$this->tt_content($t3ArrKeys[1]);
				break;
			case "PAGECONTENT" :
				$res =  $GLOBALS["TYPO3_DB"]->sql_query("SELECT bodytext FROM tt_content WHERE header='".$t3ArrKeys[1]."' AND pid = ".$this->targetUid."  ");
				$row = mysql_fetch_row($res);
				$returnval =$row[0];
				break;
			case "PAGECONTENT_HTMLTEXT" :
				$res =  $GLOBALS["TYPO3_DB"]->sql_query("SELECT bodytext FROM tt_content WHERE header='".$t3ArrKeys[1]."' AND pid = ".$this->targetUid."  ");
				$row = mysql_fetch_row($res);
				$returnval ='<![CDATA['.$row[0].']]';
				break;
			case "PAGECONTENT_IMAGE" :
				$returnval ="uploads/pics/".$this->tt_contentImage($t3ArrKeys[1]); 
				break;
			case "PAGECONTENT_TEXT" :
				$res =  $GLOBALS["TYPO3_DB"]->sql_query("SELECT bodytext FROM tt_content WHERE header='".$t3ArrKeys[1]."' AND pid = ".$this->model->currentPageInfo['uid']."  ");
				$row = mysql_fetch_row($res);
				$returnval =htmlspecialchars(utf8_encode($row[0]));
				break;	
			case "CONTENTPNG" :
				if ($this->tt_contentImage($t3ArrKeys[1])) {
					$returnval = trim($GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['absTypo3FileDocRoot'])."uploads/pics/".$this->tt_contentImage($t3ArrKeys[1]);
					
				} else {
					$returnval =  trim($GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['absTypo3FileDocRoot']).'fileadmin/flexFiles/src'.dirname(t3lib_befunc :: getRecordPath($this->model->currentPageInfo['uid'], '', '', 0)) . '/styleImages/'.$t3ArrKeys[1].'.png';
				}
				break;
			case "CONTENTSWF" :
				if ($this->tt_contentImage($t3ArrKeys[1]).length > 0) {
					$returnval = trim($GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['absTypo3FileDocRoot'])."uploads/pics/".$this->tt_contentImage($t3ArrKeys[1]);
				} else {
					$returnval =  trim($GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['absTypo3FileDocRoot']).'fileadmin/flexFiles/src'.dirname(t3lib_befunc :: getRecordPath($this->model->currentPageInfo['uid'], '', '', 0)) . '/styleImages/'.$t3ArrKeys[1].'.swf';
				}
				break;
			case "TABLE" :
				$returnval = $this->anyTable($t3ArrKeys[1],$t3ArrKeys[2],$t3ArrKeys[3],$t3ArrKeys[4]);
				break;
				case "MEDIA" :
				$returnval = $GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['absTypo3FileDocRoot']."uploads/media/".$this->anyTable($t3ArrKeys[1],$t3ArrKeys[2],$t3ArrKeys[3],$t3ArrKeys[4]);
				break;
		}
	
		return $returnval;
	}
		
		
 }
 
?>
