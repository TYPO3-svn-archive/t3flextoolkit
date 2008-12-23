/**
* ...
* @author Default
* @version 0.1
*/

package as3editor.outliner.data 
{
	import as3editor.outliner.icons.IconRefs;
	
	import nl.mopics.collections.LinkedList;
	import nl.mopics.collections.iterators.IIterator;
	
	public class MethodItem extends AOutlineItem 
	{
		private const scanType:RegExp = /[^():]\w+/;
		public var acces:String="";
		public var acces2:String="";
		public var name:String="";
		public var head:String="";
		public var returnType:String="";
		public var params:LinkedList;
		public var body:String="";
		
		/**
		 * @param a blabla
		 * @param a2 blablalba
		 */
		public function MethodItem(a:String, a2:String, n:String, h:String, t:String, b:String, mf:String ) 
		{
			acces = a;
			acces2 = a2;
			name = n;
			if( h )
			{
				head = h;
				//TODO match for parameters
				disectHead();
			}
			if( t )
			{
				var r:Array = scanType.exec(t);
				if(r)
					returnType = r[0];
			}
			body  = b;
			_matchfor = mf;
		}
		private function disectHead():void
		{
			var tr:RegExp = /\w/;
			//strip ( ) : .*
			var s:String = head.replace( /\(/, "" );
			s = s.replace(/\).*/, "" );
			if( tr.test(s) )
			{
				params = new LinkedList();
				//split ,
				var a:Array = s.split(",");
				
				for( var i:uint=0;i<a.length;i++)
				{
					var p:Array = a[i].split(":");
					var param:MethodParamItem = new MethodParamItem();
					param.name = p[0].replace(/\s/,"");
					if( p[1] )
						param.type = p[1].replace(/\s/,"");
					
					params.add( param );
				}
			}
		}
		
		public override function getOutlineViewNode():XML
		{
			//TODO create and append JavaDOC nodes
			var o:XML = <function label="" iconType="" />;
			o.@label = name+head;
			o.@acces = acces;
			o.@acces2 = acces2;
			o.@id = name;
			o.@returnType = returnType;
			o.@head = head;
			o.@isBranch = "false";
			o.@match = _matchfor;
			//TODO determin which icon to use
			if( acces=="public" )
			{
				o.@iconType = IconRefs.ICN_PUBLIC_METHOD;
			}
			else
			{
				o.@iconType = IconRefs.ICN_PRIVATE_METHOD;
			}
			//append javadoc
			//if( _jd )
			//	o.appendChild( _jd.getOutlineViewNode() );
			
			//append parameters
			if( params )
			{
				var pm:XML = <params />;
				var itr:IIterator = params.iterator();
				while( itr.getCurrent() )
				{
					pm.appendChild( itr.getCurrent().getOutlineViewNode() );
					itr.next();
				}
				o.appendChild(pm);
			}
			return o;
		}
	}
}
