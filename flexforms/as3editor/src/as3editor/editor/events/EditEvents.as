package as3editor.editor.events
{
	import flash.events.Event;

	public class EditEvents extends Event
	{
		public static const HEAD_CHANGE:String="changeHead";
		public static const TAIL_CHANGE:String="changeTail";
		
		public function EditEvents(type:String, bubbles:Boolean=false, cancelable:Boolean=false)
		{
			super(type, bubbles, cancelable);
		}
		
	}
}