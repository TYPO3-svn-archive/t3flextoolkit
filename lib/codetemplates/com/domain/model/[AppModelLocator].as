package com.domain.model {


	import com.adobe.cairngorm.model.ModelLocator;
	import mx.logging.ILogger;
	
	

 	[Bindable]
	public class [AppModelLocator] implements ModelLocator {
	
		private static var model : AppModelLocator;

		public function AppLocator() : void {
			if ( AppModelLocator.model != null )
				throw new Error( "Only one ModelLocator instance should be instantiated" );
			}

		// singleton: always returns the one existing static instance to itself
		public static function getInstance() : AppModelLocator {
			if ( model == null )
			model = new AppModelLocator();
			return model;
			}
		}
} 