package as3editor.outliner.data
{
	public class MethodParamItem extends AOutlineItem
	{
		private var _name:String;
		private var _type:String;
		private var _javadoc:String;
		
		public function MethodParamItem()
		{
			super();
		}
		
		public function get name():String
		{
			return _name;
		}
		public function set name(s:String):void
		{
			_name = s;
		}
		public function get type():String
		{
			return _type;
		}
		public function set type(s:String):void
		{
			_type = s;
		}
		public function get javadoc():String
		{
			return _javadoc;
		}
		public function set javadoc(s:String):void
		{
			_javadoc = s;
		}
		
		public override function getOutlineViewNode():XML
		{
			var x:XML = <param />;
			x.@id = _name;
			x.@type = _type;
			//x.@javadoc = _javadoc;
			
			return x;
		}
	}
}