

package com.domain.business {

	import com.adobe.cairngorm.business.ServiceLocator;

	import mx.rpc.AsyncToken;
	import mx.rpc.IResponder;
	
	public class [httpDelegate]
	{
		private var responder : IResponder;
		private var service : Object;
				
	public function [httpDelegate]( responder : IResponder )
	{
		this.service = ServiceLocator.getInstance().getHTTPService('');
		this.command = command;
	}
		
	public function (): void 
	{
		var token:AsyncToken = service.send();
		token.addResponder( command );	
	}
	public function test(){
	
	}
	}
}