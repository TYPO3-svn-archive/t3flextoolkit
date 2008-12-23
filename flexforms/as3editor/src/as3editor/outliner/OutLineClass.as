/**
* ...
* @author Default
* @version 0.1
*/

package as3editor.outliner 
{
	import as3editor.outliner.data.*;
	
	import flash.events.EventDispatcher;

	public class OutLineClass extends EventDispatcher
	{
		private var data:OutlineData = new OutlineData();
		
		public function OutLineClass() 
		{
			
		}
		/**
		 * Constructs this outliner via appropiate XML ( see //TODO ?doc how this xml should be layed out )
		 * @param	xml
		 */
		public function constructFromXML( xml:XML ):void
		{
			//TODO construct outline form xml
		}
		/**
		 * Analizes an entire class-source-code
		 * @param	source
		 */
		public function contructFromSource( src:String ):void
		{
			data.clear();
			//first find matches for Javadoc entries
			Parse.RXP_JAVADOC_G.lastIndex = 0;
			var jd:Array = Parse.RXP_JAVADOC_G.exec(src);
			while( jd )
			{
				data.addJavadoc( new JavadocItem( jd ) );
				jd = Parse.RXP_JAVADOC_G.exec(src);
			}
			//remove comments and quotes
			var source:String = Parse.removeComments( src );
			source = Parse.removeQuotes( source );
			source = Parse.removeRegExps( source );
			//var source:String = src;
			//find package
			Parse.RXP_PACKAGE.lastIndex = 0;
			var pr:Array = Parse.RXP_PACKAGE.exec( source );
			if (!pr) return;
			data.packagePath =  pr[Parse.RIDX_PACKAGE_PATH];
			data.packageBody =  pr[Parse.RIDX_PACKAGE_BODY];
			
			//find classes
			var classRXP:RegExp = Parse.RXP_CLASS;
			classRXP.lastIndex = 0;
			var cr:Array = classRXP.exec( data.packageBody );
			// g flag is set in classRXP ( multiple classes in one package ) so I can use a while loop
			while ( cr )
			{
				if ( cr )
				{
					//if a class was found and its left-brace -> find the counter brace and skip the regexp to that index
					if( Parse.BRACE_LEFT.test(cr[Parse.RIDX_CLASS_LEFTBRACE]) )
					{
						var skipTo:int = Parse.getRigthCounterBraceIndex( classRXP.lastIndex-1, data.packageBody );
						//put all body code in where first only left-brace was
						cr[Parse.RIDX_CLASS_LEFTBRACE] = data.packageBody.slice(classRXP.lastIndex-1,skipTo+1);
						classRXP.lastIndex = skipTo;
					}
					//construct data.ClassItem from found array
					data.addClass( new ClassItem( cr, data ) );
				}
				cr = classRXP.exec( data.packageBody );
			}
			
			//TODO find package import statements
			var impRxp:RegExp = Parse.RXP_IMPORT_PATH;
			impRxp.lastIndex = 0;
			var imp:Array = impRxp.exec(data.packageBody);
			while ( imp )
			{
				data.addImport(new ImportItem(imp));
				imp = impRxp.exec(data.packageBody);
			}
			
			var e:OutlineDataEvent = new OutlineDataEvent( OutlineDataEvent.TOTAL_UPDATE );
			dispatchEvent(e);
		}
		/**
		 * Removes code from source and analizes what this means for the class-outline
		 * @param	startIndex
		 * @param	endIndex
		 * @param	source
		 */
		public function removeCode( startIndex:int, endIndex:int, source:String ):void
		{
			//TODO remove code and re-analize
		}
		/**
		 * Analizes a piece of code in a given source
		 * @param	startIndex index of the source to start analizing
		 * @param	endIndex index of the source to end analizing
		 * @param	source the newly updated source to be analized
		 */
		public function analizePart( startIndex:int, endIndex:int, source:String ):void
		{
			//TODO Find out what scope the source-part is in
			
		}
	
		/**
		 * Returns current OutlineData as XML 
		 */
		public function getXML():XML
		{
			//return data.getAsXML();
			return new XML();
		}
		
		/**
		 * Returns current OutlineData as a usable Object for OutlinView's dataprovider
		 */
		public function getData():OutlineData
		{
			return data;
		}
		private function analizePackageScope( startIndex:int, endIndex:int, source:String ):void
		{
			
		}
		private function analizeClassScope( startIndex:int, endIndex:int, source:String ):void
		{
			
		}
		private function analizeMethodScope( startIndex:int, endIndex:int, source:String ):void
		{
			
		}
		
		
	}
}
