
/***************************************************************
* Sat, 13 Dec 08 22:20:11 +0100
* 
***************************************************************/

package com.domain.command {
	
	
	import com.domain.business.*;
	import com.domain.control.*;
	import com.domain.vo.*;

	import com.domain.model.AppModelLocator;

	import com.adobe.cairngorm.commands.Command;
	import com.adobe.cairngorm.control.CairngormEvent;
	import com.adobe.cairngorm.control.CairngormEventDispatcher;
	
	import mx.collections.ArrayCollection;
	import mx.rpc.IResponder;

	public class [rpcDelegatedCommand] implements Command, IResponder {

		private var model : AppModelLocator = AppModelLocator.getInstance();
		
		public function execute( cgEvent:CairngormEvent ) : void {
			var delegate :  = new (this);
			delegate.();
			}
		public function result( rpcEvent : Object ) : void {
			// model.listDP = new ArrayCollection(rpcEvent.result);	
			}
		public function fault( rpcEvent : Object ) : void {
			// model.errorStatus = "Fault occured in [rpcDelegatedCommand].";
			}
		}
	}
