/**
* ...
* @author Default
* @version 0.1
*/

package as3editor.outliner.data 
{
	import flash.events.*;

	public class OutlineDataEvent extends Event 
	{
		public static const TOTAL_UPDATE:String = "tupdate";
		public static const METHOD_UPDATE:String = "mupdate";
		public static const MEMBER_UPDATE:String = "meupdate";
		public static const CLASS_UPDATE:String = "cupdate";
		public static const IMPORT_UPDATE:String = "iupdate";
		public static const PACKAGEP_UPDATE:String = "pupdate";
		
		public function OutlineDataEvent( s:String ) 
		{
			super(s);
		}
	}
}