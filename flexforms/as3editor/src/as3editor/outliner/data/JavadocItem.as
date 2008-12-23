package as3editor.outliner.data
{
	import as3editor.outliner.Parse;
	
	import nl.mopics.collections.LinkedList;
	import nl.mopics.collections.iterators.IIterator;
	
	public class JavadocItem extends AOutlineItem
	{
		private var params:LinkedList = new LinkedList();
		private var _name:String;
		private var _type:String;
		private var _text:String;
		
		public function JavadocItem( res:Array )
		{
			super();
			disectJavadocResult( res );
		}
		
		private function disectJavadocResult( res:Array ):void
		{
			var MainDoc:RegExp  = /(.[^@])*/m;
			var ParamDoc:RegExp = /(@\w*)(.*)/g;
			var Results:Array = new Array();
			_name = res[Parse.RIDX_JDOC_NAME].replace(/\s*/,"");
			_type = res[Parse.RIDX_JDOC_NAME];
			_text = MainDoc.exec(res[0])[0].replace(/\s[*\/]/,"");
			var Result:Array = ParamDoc.exec(res[0]);
			while( Result )
			{
				params.add( new JavadocParamItem(Result) );
				Result = ParamDoc.exec(res[0]);
			}
		}
		
		public function get name():String
		{
			return _name;
		}
		public function get text():String
		{
			return _text;
		}
		public override function getOutlineViewNode():XML
		{
			var o:XML = <javadoc />;
			o.@id = _name;
			o.@type = _type;
			o.@text = _text;
			var itr:IIterator = params.iterator();
			while( itr.getCurrent() )
			{
				o.appendChild( itr.getCurrent().getOutlineViewNode() );
				itr.next();
			}
			return o;
		}
	}
}