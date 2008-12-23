package com.domain.command {

	//typo3 relative classPaths from comname/domainname
	import com.domain.business.*;
	import com.domain.model.AppModelLocator;
	
	import com.adobe.cairngorm.commands.Command;
	import com.adobe.cairngorm.control.CairngormEvent;

	public class [CairngormCommand] implements Command {

		private var model : AppModelLocator = AppModelLocator.getInstance();
		
		public function execute( cgEvent:CairngormEvent ) : void {
			// do something
		}
		
			
		}
} 