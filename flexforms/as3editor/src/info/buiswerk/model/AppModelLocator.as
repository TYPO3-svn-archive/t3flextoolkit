package info.buiswerk.model {


	import com.adobe.cairngorm.model.ModelLocator;
	

 	[Bindable]
	public class AppModelLocator implements ModelLocator {
	
	private static var model : AppModelLocator;
			
			public var applicationMainView:uint = 0;
			
			
			public var code:String;
			public var tokenstructure:XML =<root><tokens>
								                <package>
								                    <class>
								                    	<declaration>
								                    			<var>
								                    				<token>var</token> 
								                    				<tokenkey>declaration.var</tokenkey>
								                    			</var> 
								                    			<publicvar>
								                    				<token>public var</token> 
								                    				<tokenkey>declaration.public.var</tokenkey>
								                    			</publicvar>
								                    			
								                    			<privatevar>
								                    				<token>private var</token> 
								                    				<tokenkey>declaration.private.var</tokenkey>
								                    			</privatevar>
								                    	</declaration>
								                    	
								                    	<method>
								                    				<params/>
								                    				
								                    				<returntype>
									                    					<string>
									                    						<token>String</token> 
									                    						<tokenkey>method.returntype.string</tokenkey>
									                    					</string>
									                    					<number>
									                    						<token>Number</token> 
									                    						<tokenkey>method.returntype.number</tokenkey>
									                    					</number> 
								                    				</returntype>
								                    				<keyword>
								                    					<keywords>
																						
																						<keyword>case</keyword>
																						<keyword>catch</keyword>
																					
																						<keyword>for</keyword>
																						<keyword>function</keyword>
																						<keyword>if</keyword>
																						
																						<keyword>this</keyword>
																						
																						<keyword>var</keyword>
																						
																						<keyword>while</keyword>
																						<keyword>with</keyword>
																				
																					
																					</keywords>
								                    				</keyword>
														</method>
													</class>
												</package>
											</tokens>
										</root>
			
		public function AppLocator() : void {
			if ( AppModelLocator.model != null )
				throw new Error( "Only one ModelLocator instance should be instantiated" );
			}
		public function testme() : Number {
			return 0;
						}
		// singleton: always returns the one existing static instance to itself
		public static function getInstance() : AppModelLocator {
			if ( model == null )
				model = new AppModelLocator();
			return model;
			}
		}
} 