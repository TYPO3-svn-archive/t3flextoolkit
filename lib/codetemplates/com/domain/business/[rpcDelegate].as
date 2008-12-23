

package com.domain.business {

	import com.adobe.cairngorm.business.ServiceLocator;
	
	import mx.rpc.AsyncToken;
	import mx.rpc.IResponder;
	
	public class [rpcDelegate]
	{
		private var responder : IResponder;
		private var service : Object;
				
		public function [rpcDelegate]( responder : IResponder )
		{
			this.service = ServiceLocator.getInstance().getService("");
			this.responder = responder;
		}
		
		public function (): void
		{
			var token : AsyncToken = service.();
			token.resultHandler = responder.result;
			token.faultHandler = responder.fault;
		}
		

	}
}