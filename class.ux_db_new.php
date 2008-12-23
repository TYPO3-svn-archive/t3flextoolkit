<?php
class ux_sc_db_new extends SC_db_new {
	
	function main() {
		global $doc;
		$mxmlurl = 'alt_doc.php?edit[pages][0]=new&defVals[pages][doktype]=' . $GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['registertodoktypesMXML'] . '&defVals[pages][title]=[classname, no extension like mxml]&defVals[pages][hidden]=0';
		$actionscripturl = 'alt_doc.php?edit[pages][' . $this->pageinfo['uid'] . ']=new&defVals[pages][doktype]=' . $GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['registertodoktypesACTIONSCRIPT'] . '&defVals[pages][title]=[classname, no extension like as]&defVals[pages][hidden]=0';
		
		
		$amfphpurl = 'alt_doc.php?edit[pages][0]=new&defVals[pages][doktype]=' . $GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['registertodoktypesAMF'] . '&defVals[pages][title]=[service classname]&defVals[pages][hidden]=0';
		
		
		$wizzard = '../typo3conf/ext/t3flextoolkit/mod3/index.php?id=' . $this->pageinfo['uid'];
		
		$flexurl = 'alt_doc.php?edit[pages][' . $this->pageinfo['uid'] . ']=new&defVals[pages][doktype]=' . $GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['registertodoktypesPROJECT'] . '&defVals[pages][title]=FLEX APPLICATION&defVals[pages][hidden]=0';
		//t3lib_div::debug($GLOBALS['T3_VAR']);
		
		if ($this->id == 0) {
			
			$this->content .= '<a href="#" onclick="window.location.href=' . "'" . $flexurl . "'" . '"> <img src="../typo3conf/ext/t3flextoolkit/icons/flexicon.gif" alt="" height="16" width="18">New t3flex Project
				</a><br /><br />';
			
		} else {
			
			if ($this->pageinfo['doktype'] == $GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['registertodoktypesPROJECT'] || $this->pageinfo['doktype'] == 254) {
				$this->content .= '<img src="../typo3conf/ext/t3flextoolkit/icons/flexicon.gif" alt="" height="16" width="18">
					
			<br/><img src="sysext/t3skin/icons/gfx/ol/join.gif" alt=""
					
					height="16" width="18">' .		'<a href="#" onclick="window.location.href=' . "'" . $amfphpurl . "&templateType=as'" . '"><img src="../typo3conf/ext/t3flextoolkit/icons/flexicon_amfMethodRecord" class="c-recIcon" alt="" title="id=2" >amfphp service</a><br />' .
'<img src="sysext/t3skin/icons/gfx/ol/join.gif" alt=""
					
					height="16" width="18"><a href="#" onclick="window.location.href=' . "'" . $wizzard . "&templateType=as'" . '"><img src="../typo3conf/ext/t3flextoolkit/icons/C_ActionScriptFile_md.png" class="c-recIcon" alt="" title="id=2" >actionScript from template</a>' .					
'<br><img src="sysext/t3skin/icons/gfx/ol/join.gif" alt="" ><a href="#"  onclick="window.location.href=' . "'" . $wizzard . "&templateType=mxml'" . '"><img src="../typo3conf/ext/t3flextoolkit/icons/C_FlexApplicationView_md.png"  class="c-recIcon"  >mxml from template</a><br /><br />';
			}
			
		}
		
		parent :: main();
		
	}
	
}
?>