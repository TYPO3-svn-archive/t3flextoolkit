package as3editor.outliner
{
	import flash.events.Event;

	public class OutlinerEvent extends Event
	{
		public static const DATA_CONSTRUCTED:String = "dataConstructed";
		public static const ON_OL_ITEM_CLICK:String = "outlineItemClick";
		
		/**
		 * beginIndex of source-code referring to the item being clicked.
		 */
		public var beginIndex:int;
		/**
		 * endIndex of source-code referring to the item being clicked.
		 */
		public var endIndex:int;
		/**
		 * debugging variable
		 */
		public var match:String;
		
		/**
		 * Constructor
		 * @param type type of the event
		 * @param bubbles Make it bubbly?
		 * @param cancelable make it cancelable?
		 */
		public function OutlinerEvent(type:String, bubbles:Boolean=false, cancelable:Boolean=false)
		{
			super(type, bubbles, cancelable);
		}
	}
}