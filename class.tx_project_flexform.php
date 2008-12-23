<?php
class tx_project_flexform {

	function getXml() {
		// if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

		// Dynamic flexform  at page table DS (typo3 flexform not flash) for custom settings (default settings at info tab of the extension manager);
		// todo: Make this multi Language...

		$application_ds = '
		<T3DataStructure>
			<meta>
			</meta>
			<sheets>
			<sDEF>
			<ROOT>
				<TCEforms>
					<sheetTitle>compiler settings</sheetTitle>
					<description></description>
				</TCEforms>
				<type>array</type>
				<el>
					<defaultInput>
			<TCEforms>
				<label>sourcepath</label>				
					<config>
						<type>select</type>
					
				
				 <itemsProcFunc>tx_flexformsettings->compilerPath</itemsProcFunc>
			  
				</config>				
				</TCEforms>
					</defaultInput>
		
		
						
		
		
					
		<deploydir>
		
		
		
		
		
						<TCEforms>
						<label>deploy dir</label>				
							<defaultExtras></defaultExtras>
						 <config type="array">
		           <type>input</type>
		             <size>40</size>
		                 <default>' . $GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['SWF_DEPLOY_DIR'] . '</default>
		                <max>256</max>
		               <wizards type="array">
		                  <_PADDING type="integer">2</_PADDING>
		                      <link type="array">
		                      <type>popup</type>
		              <title>Link</title>
		       <icon>link_popup.gif</icon>
		<script>browse_links.php?mode=wizard</script>
		<JSopenParams>height=300,width=500,status=1,menubar=1,scrollbars=1</JSopenParams>
		                                             </link>
		                                         </wizards>
		                                     </config>
						</TCEforms>
					</deploydir>
					
				
		
		
					
						<coptions>
						<TCEforms>
						<label>additional compiler options</label>				
							<defaultExtras></defaultExtras>
						<config>
							<type>text</type>					
								<rows>4</rows>		
								<cols>20</cols>	
						</config>
						</TCEforms>
					</coptions>			
				</el>
			</ROOT>
			</sDEF>
			<sDEF3>
			<ROOT>
				<TCEforms>
					<sheetTitle>target</sheetTitle>
				</TCEforms>
				<type>array</type>
				<el>
			
			
			
			
			
			
				<classpath>
				<TCEforms>
        <exclude>1</exclude>
        <label>targetClass</label>
        <config>
         <type>group</type>
         <internal_type>db</internal_type>
       	  <allowed>pages</allowed>
         <size>1</size>
         <maxitems>1</maxitems>
         <minitems>0</minitems>
         <show_thumbs>1</show_thumbs>
        </config>
       </TCEforms>
		</classpath>
						</el>
			</ROOT>
			</sDEF3>
			
		<sDEF4>
			<ROOT>
				<TCEforms>
					<sheetTitle>swf settings</sheetTitle>
				</TCEforms>
				<type>array</type>
				<el>
		
					<framerate>
						<TCEforms>
						<label>framerate</label>				
							<defaultExtras></defaultExtras>
						<config>
							<type>input</type>					
								<size>3</size>
								    <default>' . $GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['DEFAULT_FRAMERATE'] . '</default>
								<eval>int</eval>
						</config>
						</TCEforms>
					</framerate>
					<height>
						<TCEforms>
						<label>height</label>				
							<defaultExtras></defaultExtras>
						<config>
							<type>input</type>					
								<size>4</size>
								    <default>' . $GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['DEFAULT_HEIGHT'] . '</default>
								<eval>int</eval>
						</config>
						</TCEforms>
					</height>
						<width>
						<TCEforms>
						<label>width</label>				
							<defaultExtras></defaultExtras>
						<config>
							<type>input</type>					
								<size>4</size>
								    <default>' . $GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['DEFAULT_WIDTH'] . '</default>
								<eval>int</eval>
						</config>
						</TCEforms>
					</width>
					
					
					<bgcolor>
						<TCEforms>
						<label>backgroundcolor</label>				
							<config>
							<type>input</type>	
							 <size>10</size>	
							       <default>' . $GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['DEFAULT_BACKGROUND_COLOR'] . '</default>
						<wizards>	
							<colorpick>
							
							<type>colorbox</type>	
							<title>bgcolor</title>	
								<script>wizard_colorpicker.php</script>					
								<dim>20x20</dim>
		<tableStyle>border: solid 1px black; margin: 20px;</tableStyle>
		<JSopenParams>height=550,width=365,status=0,menubar=0,scrollbars=1</JSopenParams>	
		<exampleImg>t3lib/gfx/wizard_colorpickerex.jpg</exampleImg>	
								
							</colorpick>	
						</wizards>			
						</config>
						</TCEforms>
					</bgcolor>	
		
		  
		
		
		
						</el>
			</ROOT>
			</sDEF4>
			
			
			<sDEF6>
			<ROOT>
				<TCEforms>
					<sheetTitle>Cairngorm settings</sheetTitle>
				</TCEforms>
				<type>array</type>
				<el>
		
		   	<comName>
				<TCEforms>
					<label>com name: (like com,org or info...)</label>
					<config>
					
								<type>input</type>					
								<size>20</size>
								<default>' . $GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['comName'] . '</default>
								<eval>trim</eval>	
					</config>
				</TCEforms>
			</comName>
			
		   	<domainName>
				<TCEforms>
					<label>domain name</label>
						<config>		
								<type>input</type>					
								<size>20</size>
								<default>' . $GLOBALS['T3_VAR']['ext']['t3flextoolkit']['setup']['domainName'] . '</default>
								<eval>trim</eval>						
						</config>
				</TCEforms>
			</domainName>
		
		
						</el>
			</ROOT>
			</sDEF6>	
				<sDEF7>
			<ROOT>
				<TCEforms>
					<sheetTitle>License</sheetTitle>
				</TCEforms>
				<type>array</type>
				<el>
		
		
			
		   	<License>
				<TCEforms>
					<label>License</label>
						<config>		
								<type>text</type>					
								<cols>40</cols>
								<rows>20</rows>
								<default></default>
						</config>
				</TCEforms>
			</License>
		
		
						</el>
			</ROOT>
			</sDEF7>
				<sDEF8>
			<ROOT>
				<TCEforms>
					<sheetTitle>Libs and Class management</sheetTitle>
				</TCEforms>
				<type>array</type>
				<el>
		
		
			
		   <templateFile>
					<TCEforms>
						<label>libs to included (SWC files)</label>
						<config>
							<type>group</type>
							<internal_type>file</internal_type>
							<allowed>zip,swc</allowed>
							<max_size>100000</max_size>
							<uploadfolder>fileadmin/flexfiles/lib/</uploadfolder>
							<maxitems>100</maxitems>
							<size>5</size>
							<selectedListStyle>Width:180px</selectedListStyle>
						</config>
					</TCEforms>
				</templateFile>

		
						</el>
			</ROOT>
			</sDEF8>
						</sheets>
		</T3DataStructure>';

		$application_ds;

		return $application_ds;
	}

}

?>