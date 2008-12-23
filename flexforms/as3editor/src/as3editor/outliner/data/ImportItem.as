/**
* ...
* @author Default
* @version 0.1
*/

package as3editor.outliner.data {

	import as3editor.outliner.Parse;
	import as3editor.outliner.icons.IconRefs;
	
	public class ImportItem extends AOutlineItem 
	{
		public var path:String;
		
		public function ImportItem( res:Array ) 
		{
			path = res[0];
			//remove endline ;
			path = path.replace(/\;/,"" );
			//remove whitespace
			path = path.replace( /\s/,"" );
			//remove import word
			path = path.replace( Parse.RXP_IMPORT,"" );
			
			_matchfor = res[0];
		}
		
		public override function getOutlineViewNode():XML
		{
			var xml:XML = <import label="" iconType="" />;
			xml.@label = path;
			xml.@id = path;
			xml.@iconType = IconRefs.ICN_IMPORT;
			xml.@match = _matchfor;
			return xml;
		}
	}
	
}
