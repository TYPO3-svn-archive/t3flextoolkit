/**
* ...
* @author Default
* @version 0.1
*/

package as3editor.outliner.data 
{
	import as3editor.outliner.*;
	import as3editor.outliner.icons.IconRefs;
	
	import nl.mopics.collections.*;
	import nl.mopics.collections.iterators.IIterator;

	public class ClassItem extends AOutlineItem
	{
		private var methods:LinkedList = new LinkedList(); 
		private var imports:LinkedList = new LinkedList(); 
		private var members:LinkedList = new LinkedList(); 
		private var getsetters:LinkedList = new LinkedList();
		
		private var _extends:String;
		private var _implements:LinkedList = new LinkedList(); 
		
		private var _classBody:String;     //class-body code
		private var _acces:String;
		private var _acces2:String;
		private var _name:String;
		private var data:OutlineData;
		
		
		/**
		 * Construct ClassItem from Parse.RXP_CLASS result array
		 * @param	regresult
		 */
		public function ClassItem(regresult:Array, data:OutlineData)
		{
			this.data = data;
			_matchfor = regresult[0];
			//construct body and name
			_classBody = regresult[Parse.RIDX_CLASS_LEFTBRACE];
			_name = regresult[Parse.RIDX_CLASS_NAME].replace(/\s*/,"");
			
			//construct acces
			var a1:Array = Parse.RXP_ACCES.exec(regresult[Parse.RIDX_CLASS_ACCES]);
			var a2:Array = Parse.RXP_ACCES.exec(regresult[Parse.RIDX_CLASS_ACCES2]);
			if ( a1 )
			{
				_acces = a1[0];
				a2 = Parse.RXP_ACCES.exec(regresult[Parse.RIDX_CLASS_ACCES]);
				if ( a2 )
					_acces2 = a2[0];
			}
			else if ( a2 )
			{
				_acces2 = a2[0];
				a2 = Parse.RXP_ACCES.exec(regresult[Parse.RIDX_CLASS_ACCES]);
				if (a2)
					_acces = a2[0];
			}
			
			//TODO construct implement and extend
			
			//TODO scan for class-entities in class body source
			var er:Array = Parse.RXP_CLASS_ENTITIES.exec(_classBody);
			while( er )
			{
				if( er )
				{
					//if a function was found and its left-brace -> find the counter brace and skip the regexp to that index
					if( Parse.BRACE_LEFT.test(er[Parse.RIDX_FUNCTION_LEFT_BRACE]) )
					{
						var skipTo:int = Parse.getRigthCounterBraceIndex( Parse.RXP_CLASS_ENTITIES.lastIndex-1, _classBody );
						//put all body code in where first only left-brace was
						er[Parse.RIDX_FUNCTION_LEFT_BRACE] = _classBody.slice(Parse.RXP_CLASS_ENTITIES.lastIndex-1,skipTo+1);
						Parse.RXP_CLASS_ENTITIES.lastIndex = skipTo;
					}
					addEntitie( er );
				}
				er = Parse.RXP_CLASS_ENTITIES.exec(_classBody);
			}
		}
		
		public function addEntitie( a:Array ,dispatchUpdateEvent:Boolean=false ):void
		{
			if ( Parse.RXP_IMPORT.test(a[Parse.RIDX_CLASS_ENTITY_TYPE]) )
			{
				var imp:ImportItem = new ImportItem(a[Parse.RIDX_CLASS_ENTITY_TYPE]);
				imports.add(imp);
				return;
			}
			
			var entityType:String;
			if( Parse.RXP_GETTER.test(a[Parse.RIDX_CLASS_ENTITY_TYPE]) )
			{
				entityType = "get";
			}
			else if( Parse.RXP_SETTER.test(a[Parse.RIDX_CLASS_ENTITY_TYPE]) )
			{
				entityType = "set";
			}
			else if (Parse.RXP_FUNCTION.test(a[Parse.RIDX_CLASS_ENTITY_TYPE]))
			{
				entityType = "function";
			}
			else if (Parse.RXP_VAR.test(a[Parse.RIDX_CLASS_ENTITY_TYPE]))
			{
				entityType = "var";
			}
			else if (Parse.RXP_CONST.test(a[Parse.RIDX_CLASS_ENTITY_TYPE]))
			{
				entityType = "const";
			}
			//ent name
			var acces:String; var acces2:String;
			var label:String = a[Parse.RIDX_CLASS_ENTITY_NAME];
			var type:String  = a[Parse.RIDX_CLASS_ENTITY_VAR_TYPE];
			//determin acces
			var a1:Array = Parse.RXP_ACCES.exec(a[Parse.RIDX_CLASS_ACCES]);
			var a2:Array = Parse.RXP_ACCES.exec(a[Parse.RIDX_CLASS_ACCES2]);
			if ( a1 )
			{
				acces = a1[0];
				a2 = Parse.RXP_ACCES.exec(a[Parse.RIDX_CLASS_ACCES]);
				if ( a2 )
					acces2 = a2[0];
			}
			else if ( a2 )
			{
				acces2 = a2[0];
				a2 = Parse.RXP_ACCES.exec(a[Parse.RIDX_CLASS_ACCES]);
				if (a2)
					acces = a2[0];
			}
			//
			if ( entityType == "var" || entityType=="const" )
			{
				var mem:MemberItem = new MemberItem(acces, acces2, label, entityType, type, a[0] );
				//find matching Javadoc
				if( data.javadocs[ mem.name ] )
					mem.setJavadoc( data.javadocs[mem.name] );
					
				type = a[Parse.RIDX_CLASS_ENTITY_VAR_TYPE];
				members.add(mem);
				return;
			}
			else if( entityType=="get" || entityType=="set" )
			{
				//first find if same name exists
				var itr:IIterator = getsetters.iterator();
				var itm:GetSetItem = itr.getCurrent() as GetSetItem;
				var e:Boolean = false;
				if( itm )
				{
					if( itm.name == name )
					{
						itm["_"+entityType] = true;
						return;
					}
					else
					{
						while( itr.hasNext() )
						{
							itm = itr.next() as GetSetItem;
							if( itm.name == name )
							{
								itm["_"+entityType] = true;
								e = true;
								break;
							}
						}
					}
				}
				//if same name doesnt exist
				if( !e )
				{
					var gs:GetSetItem;
					
					if( entityType == "get" )
						gs = new GetSetItem( label, a[Parse.RIDX_CLASS_ENTITY_VAR_TYPE], a[0], true );
					else
						gs = new GetSetItem( label, a[Parse.RIDX_CLASS_ENTITY_VAR_TYPE], a[0], false, true );
					
					//find matching Javadoc
					if( data.javadocs[ gs.name ] )
						gs.setJavadoc( data.javadocs[gs.name] );
					
					getsetters.add( gs );
				}
			}
			else
			{
				var mth:MethodItem = new MethodItem( acces, 
										acces2, 
										label, 
										a[Parse.RIDX_CLASS_ENTITY_FUNCTION_HEAD], 
										a[Parse.RIDX_FUNCTION_RETURNTYPE], 
										a[Parse.RIDX_FUNCTION_LEFT_BRACE],
										a[0] );
				//find matching Javadoc
				if( data.javadocs[mth.name] )
					mth.setJavadoc( data.javadocs[mth.name] );
					 
				methods.add(mth);
			}
		}
		
		public function get classBody():String
		{
			return _classBody;
		}
		
		public function set classBody( s:String ):void
		{
			_classBody = s;
		}
		
		public function get name():String
		{
			return _name;
		}
		public override function getOutlineViewNode():XML
		{
			var o:XML = <class label="" iconType="" />;
			o.@label     = _name;
			o.@iconType  = IconRefs.ICN_CLASS;
			o.@id = _name;
			o.@match = this._matchfor;
				
			//iterate thru members and methods
			var itr:IIterator = members.iterator();
			if( itr.getCurrent() )
			{
				o.appendChild(itr.getCurrent().getOutlineViewNode());
				while( itr.hasNext())
				{
					o.appendChild(itr.next().getOutlineViewNode());
				}
			}
			//methods
			itr = methods.iterator();
			if( itr.getCurrent() )
			{
				o.appendChild(itr.getCurrent().getOutlineViewNode());
				while( itr.hasNext())
				{
					o.appendChild(itr.next().getOutlineViewNode());
				}
			}
			//getsetters
			itr = getsetters.iterator();
			if( itr.getCurrent() )
			{
				o.appendChild(itr.getCurrent().getOutlineViewNode());
				while( itr.hasNext())
				{
					o.appendChild(itr.next().getOutlineViewNode());
				}
			}
			//TODO iterate thru extends, implements
			
			return o;
		}
	}
}
