package as3editor.utils.data {
	import as3editor.outliner.data.ImportItem;
	

	
	
	import as3editor.utils.data.SimXmlConnector;
	import as3editor.utils.data.DataEvents;
	import mx.core.UIComponent;
	

	public class appletLoader extends UIComponent {


	
		public var simXml:SimXmlConnector;
		public var keywords:XMLList;
	
		
	

		public function appletLoader(url:String) {

			simXml=new SimXmlConnector(url);
			simXml.callback=this.getXml;
			simXml.init();
		}
		public function getXml(xml:XML):void {

			    keywords=new XMLList(xml.keywords.keyword);
			    trace(keywords);
				dispatchEvent(new DataEvents(DataEvents.KEYWORDS_LOADED));
			
		}
		
		
		
		

			
			public function getCoreKeyWords():XMLList {
			
				
				return keywords;
			
			}
			
		
			
			
			

		}
		
		
		
		
		
		
		
	}
