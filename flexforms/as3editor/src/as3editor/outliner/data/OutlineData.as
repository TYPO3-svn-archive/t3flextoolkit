/**
* ...
* @author Default
* @version 0.1
*/

package as3editor.outliner.data
{
	import flash.events.*;
	import flash.net.*;
	
	import nl.mopics.collections.*;
	import nl.mopics.collections.iterators.*;

	public class OutlineData extends EventDispatcher 
	{
		private var _packagePath:String;
		private var _packageBody:String;
		private var _classes:LinkedList = new LinkedList();
		private var _imports:LinkedList = new LinkedList();
		private var _javadocs:Object = new Object();
		
		public function OutlineData()
		{
			
		}
		
		public function addClass( cls:ClassItem, dispatchUpdateEvent:Boolean=false ):void
		{
			if( _javadocs[cls.name] )
				cls.setJavadoc( _javadocs[cls.name] );
			
			_classes.add( cls );
		}
		public function addJavadoc( jd:JavadocItem,  dispatchUpdateEvent:Boolean=false ):void
		{
			_javadocs[jd.name] = jd;
		}
		
		public function addImport( imp:ImportItem, dispatchUpdateEvent:Boolean=false ):void
		{
			imports.add( imp );
		}
		
		public function set packagePath( s:String ):void
		{
			_packagePath = s;
		}
		
		public function get packagePath():String
		{
			return _packagePath;
		}
		
		public function set packageBody( s:String ):void
		{
			_packageBody = s;
		}
		
		public function get packageBody():String
		{
			return _packageBody;
		}
		
		public function get classes():LinkedList
		{
			return _classes;
		}
		public function get imports():LinkedList
		{
			return _imports;
		}
		public function get javadocs():Object
		{
			return _javadocs;
		}
		public function clear():void
		{
			_classes.clear();
			_imports.clear();
		}
	}
}
