package as3editor.editor
{
	import flash.text.TextField;
	
	public class documentSelection
	{		
			public var textField:TextField;
			public var markup:TextField;
			public var offset:Number;
			public var name:String;
			public var length:Number;
			
			
		public function documentSelection(src:TextField,markup:TextField)		
		{	
			this.textField=src;
			this.markup=markup;
			this.name = src.name;
			this.offset = markup.selectionBeginIndex;
			this.length = markup.selectedText.length;
			//markup.alwaysShowSelection=true;
		}
		
		
		public function removeSelection():void {
			var start:String=textField.text.slice(0,offset);
			var end:String=textField.text.slice(offset+length,textField.text.length);
			textField.text=start+end;
			//sync();
		}
		
		public function focustextFieldSelection():void {
			textField.setSelection(offset,offset+length);
			
		}
		
		public function sync():void {
		//	
		//	markup.htmlText=textField.text;
		//	markup.setSelection(textField.selectionBeginIndex,textField.selectionBeginIndex);
		//	this.offset = textField.selectionBeginIndex;
		//	this.length = textField.selectedText.length;
			
			
		}
		//backspace
		public function backspace(charBackup:String):void {
			var start:String=textField.text.slice(0,offset);
			var end:String=textField.text.slice(offset+length,textField.text.length);
			textField.text=start+charBackup+end;
			sync();
		}
		
		public function replaceSelection(replacement:String):void {
			var start:String=textField.text.slice(0,offset);
			var end:String=textField.text.slice(offset+length,textField.text.length);
			textField.text=start+replacement+end;
		//	sync();
		}
		
		
		
	}
}