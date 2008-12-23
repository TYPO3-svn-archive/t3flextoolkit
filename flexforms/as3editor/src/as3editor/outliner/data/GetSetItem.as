package as3editor.outliner.data
{
	import as3editor.outliner.icons.IconRefs;
	
	public class GetSetItem extends AOutlineItem
	{
		private const scanType:RegExp = /[^():]\w+/;
		private var _name:String;
		private var _type:String;
		public var _get:Boolean;
		public var _set:Boolean;
		
		public function GetSetItem(n:String, t:String, m:String, g:Boolean=false, s:Boolean=false )
		{
			super();
			_matchfor = 
			_name = n;
			_get = g;
			_set = s;
			var r:Array = scanType.exec(t);
			if( r )
				_type = r[0];
		}
		public function get name():String
		{
			return _name;
		}
		public function get type():String
		{
			return _type;
		}
		public override function getOutlineViewNode():XML
		{
			var xml:XML = < getset label="" iconType="" />;
			xml.@label    = _name+" : "+_type;
			xml.@iconType = IconRefs.ICN_SETGET;
			xml.@id = _name;
			xml.@type = _type;
			xml.@match = _matchfor;
			return xml;
		}
	}
}