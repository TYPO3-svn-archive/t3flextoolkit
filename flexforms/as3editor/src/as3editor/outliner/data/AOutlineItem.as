/**
* ...
* @author Default
* @version 0.1
*/

package as3editor.outliner.data 
{
	import nl.mopics.collections.ILinkedListNode;

	public class AOutlineItem implements ILinkedListNode 
	{
		
		private var _next:ILinkedListNode;
		private var _prev:ILinkedListNode;
		private var _index:uint;
		protected var _jd:JavadocItem;
		protected var _matchfor:String;
		
		public function AOutlineItem() 
		{
			
		}
		
		/*
		 * Linkedlist-node interface
		 */
		public function next():ILinkedListNode
		{
			return _next;
		}
		public function prev():ILinkedListNode
		{
			return _prev;
		}
		public function setNext(illn:ILinkedListNode):void
		{
			_next = illn;
		}
		public function setPrev(illn:ILinkedListNode):void
		{
			_prev = illn;
		}
		public function setIndex(i:Number):void
		{
			_index = i;
		}
		public function getIndex():Number
		{
			return _index;
		}
		/*
		 * END--Linkedlist-node interface
		 */
		 
		public function getOutlineViewObject():Object
		{
			return new Object();
		}
		public function getOutlineViewNode():XML
		{
			return new XML();
		}
		public function setJavadoc( jd:JavadocItem ):void
		{
			_jd = jd;
		}
	}
}
