/**
* ...
* @author Default
* @version 0.1
*/

package as3editor.outliner.data {

	import as3editor.outliner.icons.IconRefs;
	
	public class MemberItem extends AOutlineItem  
	{
		private const scanType:RegExp = /[^():]\w+/;
		public var acces:String;
		public var acces2:String;
		public var name:String;
		public var memberType:String;
		public var type:String;
		
		public function MemberItem(a:String, a2:String, n:String, mt:String,t:String, mf:String ) 
		{
			acces = a;
			acces2 = a2;
			name = n;
			memberType = mt.replace(/\s/,"");
			var r:Array = scanType.exec(t);
			if( r )
			{
				type = r[0];
			}
			_matchfor = mf;
		}
		
		public override function getOutlineViewNode():XML
		{
			var o:XML = <{memberType} label="" iconType="" />;
			o.@label = name+" : "+type;
			o.@acces = acces;
			o.@acces2 = acces2;
			o.@type = type;
			o.@id = name;
			o.@isBranch = "false";
			o.@match = _matchfor;
			// determin which icon to use
			if(memberType=="var")
			{
				if( acces=="public" )
					o.@iconType = IconRefs.ICN_PUBLIC_VAR;
				else
					o.@iconType = IconRefs.ICN_PRIVATE_VAR;
			}
			else
			{
				if( acces=="public" )
					o.@iconType = IconRefs.ICN_PUBLIC_CONST;
				else
					o.@iconType = IconRefs.ICN_PRIVATE_CONST;
			}
			//append javadoc
			//if( _jd )
			//	o.@javadoc = _jd.text;
			
			return o;
		}
	}
	
}
