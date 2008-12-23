package as3editor.outliner.data
{
	public class JavadocParamItem extends AOutlineItem
	{
		private var _name:String;
		private var _text:String;
		
		public function JavadocParamItem( res:Array )
		{
			_name = res[1];
			_text = res[2];
		}
		
		public override function getOutlineViewNode():XML
		{
			var o:XML = < jdparam />;
			o.@id = _name;
			o.@text = _text;
			return o;
		}
	}
}