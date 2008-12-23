<?php



require_once ($BACK_PATH."../typo3conf/ext/t3flextoolkit/lib/model/class.tx_t3flextoolkit_model.php");
class tx_t3flextoolkit_code_event {
 	
 	
 	
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
    
    function runEditor() 
    { 
    if (t3lib_div::_GP('SpecialRequest'))	{
			
			//t3lib_div::debug();
				$this->model->codeString = t3lib_div::_GP('SpecialRequest');
			}
			
			return $this->parseEditor();
    }
    
     function saveCode() 
   	{
        
    				 if (!defined ('TYPO3_MODE')) 	die ('Access denied.');		
					
						      				
						$flexform='<?xml version="1.0" encoding="iso-8859-1" standalone="yes" ?>
						<T3FlexForms>
						    <data>
						        <sheet index="sDEF">
						            <language index="lDEF">
						                <field index="templatefiles">
						                    <value index="vDEF">'.$this->model->currentPageInfo['title'].'</value>
						                </field>
						                <field index="editor">
						                    <value index="vDEF">'.$this->model->codeString.'</value>
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
									
						
						
						 $tce = t3lib_div::makeInstance('t3lib_TCEmain');
						
						$data['pages'][$this->model->currentId] = array(
						
						    "title" => $this->model->currentPageInfo['title'],
							"tx_flextoolkit_ds" =>$flexform,
						    "no_cache" => "1"
						
						);
						$tce->stripslashes_values = 0;
						$tce->reverseOrder = 1;
						$tce->start($data,array());
						$tce->process_datamap();
						$tce->process_cmdmap();
						t3lib_BEfunc::getSetUpdateSignal('updatePageTree');

      			
    }
    
    
    function parseEditor() {
    	
    		return '

<div class="buttongroup">
			<input type="image" onclick="document.editform.SpecialRequest.value=defid.getCode();document.editform._save.submit();" 
			name="save"  class="c-inputButton" src="../../../../typo3/sysext/t3skin/icons/gfx/savedok.gif" width="16" height="16" title="Save document" />
				<input type="button" onclick="defid.toggleLineNumbers()" value="show/hide line numbers"/>
<input type="button" onclick="defid.toggleAutoComplete()" value="turn on/off auto-complete"/>

	<input type="button" onclick="defid.toggleEditor()" value="turn on/off CodePress"/>
	
	<input type="submit" name="static" value="parse static code"/>
	<input type="submit" onclick="document.editform.SpecialRequest.value=defid.getCode();document.editform._save.submit();" name="generate" value="save static code"/>
	
<input type="button" onclick="defid.toggleReadOnly()" value="turn on/off read only"/>
	<input type="submit" name="snipplr_save"  onclick="document.editform.SpecialRequest.value=defid.getCode();document.editform._save.submit();"   value="save_snippet"/>

	<input type="hidden" name="_save"/>
	
	<input type="hidden" onclick="document.editform.SpecialRequest.value=defid.getCode();" name="to_static_template" value="copy code"/>
			<input type="hidden" name="classname" value="'.$this->model->currentPageInfo['title'].'"/>			
				
				</div>

			
<TEXTAREA NAME="SpecialRequest" style="position:absolute;left=-1000;visibility:hidden;overflow:hidden;" 
   ROWS="3" COLS="25">
</TEXTAREA>

<textarea id="defid" style="width:95%;height:70%;"   wrap="virtual"  class="codepress '.$this->model->codeType.'" 
name="mijneditor" value="test">'.$this->model->codeString.'</textarea>
<script language="javascript" type="text/javascript">
codeedit.edit(document.getElementById('."'defid'".').innerHTML,"html");
	</script>

	
';
	
    }
}
 
?>
