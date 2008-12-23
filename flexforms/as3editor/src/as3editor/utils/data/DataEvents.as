package as3editor.utils.data
{
	import flash.events.Event;

	public class DataEvents extends Event
	{
		public static const KEYWORDS_LOADED:String="keywordsloaded";
		
		
		public function DataEvents(type:String, bubbles:Boolean=false, cancelable:Boolean=false)
		{
			super(type, bubbles, cancelable);
		}
		
	}
}