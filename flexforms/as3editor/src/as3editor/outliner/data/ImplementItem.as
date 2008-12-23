/**
* ...
* @author Default
* @version 0.1
*/

package as3editor.outliner.data {

	public class ImplementItem extends AOutlineItem 
	{
		
		public function ImplementItem() 
		{
			
		}
		public override function getOutlineViewObject():Object
		{
			var o:Object = new Object();
			o.label = name;
			//TODO determin which icon to use
			return o;
		}
	}
}
