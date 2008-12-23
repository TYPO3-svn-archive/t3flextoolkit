/*
 * runtime ActionScript3 code highlighter
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the 
 * GNU Lesser General Public License as published by the Free Software Foundation.
 *  
 * Read the full licence: http://www.opensource.org/licenses/lgpl-license.php
 */


package as3editor.editor
{
	import as3editor.editor.lang.Actionscript3HighlightingDefinitions;
	
	import flash.events.Event;
	import flash.events.FocusEvent;
	import flash.events.KeyboardEvent;
	import flash.events.MouseEvent;
	import flash.events.TextEvent;
	import flash.events.TimerEvent;
	import flash.text.TextFormat;
	import flash.text.TextLineMetrics;
	import flash.ui.Keyboard;
	import flash.utils.Timer;
	
	import mx.containers.Canvas;
	import mx.controls.TextArea;
	import mx.core.Application;
	import mx.core.UIComponent;
	import mx.core.UITextField;
	import mx.core.mx_internal;
	import mx.events.*;



//--------------------------------------
//  constructor
//--------------------------------------
	
	public class EditorTextFormat extends Canvas
	{	
		
		//--------------------------------------
		// AS3 HIGHLIGHT REGEXPRESSIONS OBJECT
		//--------------------------------------
	
		public var language:Actionscript3HighlightingDefinitions;
		
		
		private var font:String="LiberationMono";
		private var scanRow:int=0;
		public var  started:Boolean=false;
		private var hold:Boolean=false;
		private var holdlong:Boolean=false;
		
		//--------------------------------------
		//  CARETPOSITION of markupTextfield
		//--------------------------------------
		
		private var _markupCaret:int;

		public function get markupCaret():int {				
					
			return markupTextfield.caretIndex;
		}
		
		public function set markupCaret(index:int):void {			
			_markupCaret=index;
		}
				
				
		//--------------------------------------
		// source code (if any)
		//--------------------------------------
		private var _source:String;
		
		[Bindable]
		public function get sourceCode():String {
			return _source;
		}
		
		public function set sourceCode(s:String):void {
			_source=s;
		}
		
		// even lines in background
		private var evenLines:Canvas;
		
		// odd lines in background
		private var oddLines:Canvas;
		
		// internal textfield of 'markup' TextArea
		public var markupTextfield:UITextField;
		
		//--------------------------------------
		//  verticalScrollPosition (if any)
		//--------------------------------------
		private var _scrollViewBeginIndex:int=1;
		
		public function get scrollViewBeginIndex():int {
				
			
		_scrollViewBeginIndex =  markupTextfield.getLineOffset(markupTextfield.scrollV-1);						
			
			//if (_scrollViewBeginIndex==scrollViewEndIndex-1) { 
			//	return markupTextfield.text.length;
			//} else { 
				return _scrollViewBeginIndex;
			//}
			
		}
		public function set scrollViewBeginIndex(index:int):void {			
			_scrollViewBeginIndex=index;
		}
		
		//--------------------------------------
		// BottomScrollPosition (if any)
		//--------------------------------------
		private var _scrollViewEndIndex:int=1;
		
		public function get scrollViewEndIndex():int {
			
			_scrollViewEndIndex   =  markupTextfield.getLineOffset(markupTextfield.bottomScrollV-1)+markupTextfield.getLineLength(markupTextfield.bottomScrollV-1);	
			return _scrollViewEndIndex;
		}
		public function set scrollViewEndIndex(index:int):void {
			_scrollViewEndIndex=index;
		}
		
		//--------------------------------------
		// overflow, so scrolling enabled?
		//--------------------------------------
		private var scrolling:Boolean;

		//--------------------------------------
		// editor TextArea 
		//--------------------------------------
		public var markup:TextArea;
		
		//--------------------------------------
		// line Numbers 
		//--------------------------------------
		private var lines:TextArea;
		
		public var editorWidth:int;
		public var editorHeight:int;
		
		public var timer:Timer;		
		public var longtimer:Timer;	
		public var hilightInbackground:Timer;	
		
		public function EditorTextFormat()
		{
			super();
			this.addEventListener(FlexEvent.CREATION_COMPLETE,initCompleted);
				
			
			
		}

//--------------------------------------
//		initCompleted
//--------------------------------------		
public function initCompleted(e:Event):void
		{	

		editorWidth=width;
		editorHeight=height;
		this.sourceCode=sourceCode;
		
		init();
		
		markup.text=this.sourceCode;
			
		timer=new Timer(500);
		timer.addEventListener(TimerEvent.TIMER,holdHandler);
		timer.start();
		
		longtimer=new Timer(2000);
		longtimer.addEventListener(TimerEvent.TIMER,holdLongHandler);
		longtimer.start();
		
		
		//hilightInbackground=new Timer(1);
		//hilightInbackground.addEventListener(TimerEvent.TIMER,backgroundScanner);
		//hilightInbackground.start();
}


//--------------------------------------
//  INIT 
//--------------------------------------

	public function init():void
		{
			
			language=new Actionscript3HighlightingDefinitions();
			

			
			setStyle("backgroundColor", 0xFFFFFF); 
			setStyle("themeColor", 0xCCCCCC);
			//setStyle("fontFamily", "Arial");
			setStyle("fontSize", 12);
			
			createTextAreas();
			
			//--------------------------------------
			//		init listeners
			//--------------------------------------
			
			
			markup.addEventListener(TextEvent.TEXT_INPUT,markupInputHandler);
			markupTextfield.alwaysShowSelection;

			// resize editor and update properties		
			Application.application.addEventListener(FlexEvent.UPDATE_COMPLETE,resizeAndUpdate);
			
			// prevent special keyactions like tab,backspace and so on			
			Application.application.addEventListener(KeyboardEvent.KEY_UP,keyCatcher);
			Application.application.addEventListener(FocusEvent.KEY_FOCUS_CHANGE,preventTabDefocus);

			markup.addEventListener(Event.SCROLL, markupScrollHandler);
			

				scrollViewBeginIndex;
				scrollViewEndIndex;
				markupCaret=markupTextfield.caretIndex;
				language.process(markupTextfield,0,markup.text.length);
				markup.setSelection(markup.selectionBeginIndex,markup.selectionBeginIndex);
				updateLineNumbers();
			
			
		}
		
//--------------------------------------
//		preventTabDefocus
//--------------------------------------	
public function preventTabDefocus(e:FocusEvent):void {
		e.preventDefault();
		
	}	
	
//--------------------------------------
//		markupFocusInHandler
//--------------------------------------		
public function markupFocusInHandler(e:FocusEvent):void
		{	
			if (started==false) {
				scrollViewBeginIndex;
				scrollViewEndIndex;
				
				markupCaret=markupTextfield.caretIndex;
				language.process(markupTextfield,0,markup.text.length);
				markup.setSelection(0,0);
				updateLineNumbers();
				started=true;
			}
				
		}
//--------------------------------------
//		backgroundScanner
//--------------------------------------		
public function backgroundScanner(t:TimerEvent):void
		{	
			if ((hold==true) && (holdlong==true)) {
				if ((scanRow+1) < markupTextfield.numLines) {
				scanRow +=1;
					language.processInBackground(markupTextfield,scanRow);
				} else {
					scanRow=-1;
					hilightInbackground.stop();
					hilightInbackground.removeEventListener(TimerEvent.TIMER,backgroundScanner);
				}
				
				//trace("scanInBackground: row:"+scanRow);
				}
		}
//--------------------------------------
//		markupFocusOutHandler
//--------------------------------------		
public function markupFocusOutHandler(e:FocusEvent):void
		{	
				

		}
		
		
//--------------------------------------
//		prevent from keys => keyCatcher
//--------------------------------------		
public function keyCatcher(e:KeyboardEvent):void
		{	
			
			e.preventDefault();
			
		
			if (e.ctrlKey==true){
				markupTextfield.text=markupTextfield.getRawText();
				hold=true;
				hilightInbackground=new Timer(1);
				hilightInbackground.addEventListener(TimerEvent.TIMER,backgroundScanner);
				hilightInbackground.start();
				
				//updateLineNumbers();
			}
			
			
			if (e.keyCode==9){
				hold=true;
				markup.setFocus();
				markupTextfield.replaceText(markupTextfield.selectionBeginIndex,markupTextfield.selectionEndIndex,"\t");
				markupTextfield.setSelection(markupTextfield.selectionBeginIndex+1,markupTextfield.selectionBeginIndex+1);
			}
			
			if (e.keyCode==Keyboard.ENTER){
				hold=true;
				holdlong=false;
				updateLineNumbers();
				//Application.application.editor.updateSource(markupTextfield.getRawText());
			}
			
			if (e.keyCode==Keyboard.BACKSPACE){
				hold=true;
				holdlong=false;
				updateLineNumbers();
			}
			
			if (e.keyCode==Keyboard.SPACE){
				hold=false;
				updateLineNumbers();
			}

			if (e.keyCode==Keyboard.UP)
			{
				updateLineNumbers();
			}
			
			if (e.keyCode==Keyboard.DOWN)
			{
				updateLineNumbers();
			}
			
			if (e.keyCode==Keyboard.LEFT )
			{

			}

			if (e.keyCode==Keyboard.RIGHT) {
			
			}
					

}

//--------------------------------------
//		resizeAndUpdate
//--------------------------------------		
public function resizeAndUpdate(e:FlexEvent):void
		{	
	
		editorWidth=width;
		editorHeight=height;
		
		evenLines.removeAllChildren();
		oddLines.removeAllChildren();
	   	createRows();

			
			lines.width  = 50;
			lines.height = editorHeight;

			markup.width  = editorWidth -(lines.width);
			markup.height = editorHeight;
			markup.x = lines.width;
			


}



//--------------------------------------
//		markup selectHandler
//--------------------------------------		
public function markupSelectDownHandler(e:Event):void
		{	
			
			
		}
		
//--------------------------------------
//		markup selectHandler
//--------------------------------------		
public function markupSelectHandler(e:MouseEvent):void
		{	
		
		}
		
//--------------------------------------
//		markup Scroll InputHandler
//--------------------------------------		
public function markupScrollHandler(e:Event):void
		{	

		if (e is ScrollEvent)
		{
			scrolling=true;
			
			var se:ScrollEvent = ScrollEvent(e);
			markupTextfield.setSelection(scrollViewEndIndex-2,scrollViewEndIndex-2);
			if (se.detail == ScrollEventDetail.THUMB_POSITION) {
				
					markupTextfield.setSelection(scrollViewEndIndex-2,scrollViewEndIndex-2);
					hold=false;
			}		
	if (se.detail == ScrollEventDetail.THUMB_TRACK) {
					if ((Math.round(scrollViewBeginIndex/2)*2)==scrollViewBeginIndex) {
					evenLines.alpha=1;
				} else { evenLines.alpha=0; }
			
			
		
			}
			}	
			updateLineNumbers();	
			if (se.detail == ScrollEventDetail.AT_BOTTOM) {
				
					//markupTextfield.setSelection(scrollViewEndIndex-2,scrollViewEndIndex-2);
					//hold=false;
			}

		}		
		
//--------------------------------------
//		holdHandler
//--------------------------------------		
public function holdHandler(t:TimerEvent):void
		{	
			if (hold==true) {
						} else {
							highlightHandler();
							updateLineNumbers();
							hold=true;
				}
		}
		
//--------------------------------------
//		holdLongHandler
//--------------------------------------		
public function holdLongHandler(t:TimerEvent):void
		{	
			if (holdlong==true) {		
						} else {
							
							holdlong=true;
							hold=false;
				}
				
		}		
		
		
//--------------------------------------
//		markupInputHandler
//--------------------------------------		
public function markupInputHandler(e:TextEvent):void
		{
				hold=true;
				holdlong=false;
				
}



//--------------------------------------
//		setNewCaretPosition
//--------------------------------------		
public function setNewCaretPosition(offset:int):void {
				
}

//--------------------------------------
//		update lineNumbers
//--------------------------------------		
public function updateLineNumbers():void {
	lines.text="";
	var b:int=markupTextfield.scrollV;
	var e:int=markupTextfield.bottomScrollV+1;
	for (var i:int=b;i<e;i++){
			lines.text = lines.text+i+"\n";
		}
}

//--------------------------------------
//		highlightHandler
//--------------------------------------		
public function highlightHandler():void
		{	
			if(!hold==true) {
				scrollViewBeginIndex;
				scrollViewEndIndex;
				markupCaret=markupTextfield.caretIndex;
				var v:int=markupTextfield.scrollV;
				language.process(markupTextfield,scrollViewBeginIndex,scrollViewEndIndex);
				markup.setSelection(markupCaret,markupCaret);
				markupTextfield.scrollV=v;
			}
		}	

//--------------------------------------
//  getTextField 
//--------------------------------------
public static function getTextField(owner:UIComponent):UITextField
		{
			return Object(owner).mx_internal::getTextField();
		}
		
//--------------------------------------
//  highligh current carets current line
//--------------------------------------	
public function markLine(e:MouseEvent):void {
    
    var c:Canvas;
    c = e.target as Canvas;
    c.graphics.clear();
    c.graphics.beginFill(0x0000FF)
    c.graphics.drawRect(0,0,20,20);
    c.graphics.endFill();
}


//--------------------------------------
//  createTextAreas 
//--------------------------------------	
private function createTextAreas():void {
		
			
			// create TextArea for line numbers
			lines=new TextArea();
			lines.width  = 50;
			lines.height = editorHeight;
			lines.setStyle("color", 0x888888);
			lines.setStyle("backgroundColor", 0xCCCCCC);
			lines.setStyle("backgroundAlpha", 0.5);
			lines.setStyle("textAlign", "center");
			lines.verticalScrollPolicy = "off";
			lines.setStyle("focusAlpha", 0);
			lines.setStyle("fontFamily",font);
			lines.setStyle("fontSize", 14);
			
			markup=new TextArea();
			markup.width  = (editorWidth)-(lines.width);
			markup.height = editorHeight;
			markup.horizontalScrollPolicy = "off";
			
			markup.setStyle("backgroundAlpha", 0);
			markup.setStyle("fontFamily", font);
			markup.setStyle("fontSize", 14);
			markup.setStyle("paddingLeft", 5);
		
			

			oddLines=new Canvas();
			oddLines.verticalScrollPolicy = "off";
			this.addChild(oddLines);
			
			evenLines=new Canvas();
			evenLines.verticalScrollPolicy = "off";
			this.addChild(evenLines);

			this.addChild(markup);
			this.addChild(lines);
			
			// CUSTOMIZE TextFields
			markupTextfield=getTextField(markup);
			markupTextfield.alwaysShowSelection=true;
			markupTextfield.embedFonts = true;
			markupTextfield.selectable = true;
            markupTextfield.mouseEnabled = true;
            markupTextfield.antiAliasType="AntiAliasType.ADVANCED";
            markupTextfield.multiline = true;
            markupTextfield.wordWrap = false;
            markupTextfield.border = false;
            
			var t:TextFormat=new TextFormat();
			t.color="#CCCCCC";
			markupTextfield.setTextFormat(t);
			

			markupTextfield.text="[]";
			
		}

//--------------------------------------
//		make grey/white background rows
//--------------------------------------	
public function createRows():void {	
	
	var p:Object=getLineProperties();
	makeRows(p.lineCount,p.lineHeight);
	trace(p.lineHeight+" - "+p.lineCount);
}

//--------------------------------------
// 		getLineHeight / line-count  of TextArea   
//--------------------------------------	
public function getLineProperties():Object {
	
	
	
	var lineMetrics:TextLineMetrics=markup.getLineMetrics(0);
	
	var lineHeight:Number=lineMetrics.height;
	var lineCount:int=markup.height/lineHeight;
	
	return {
				lineHeight: lineHeight,
				lineCount: lineCount
		};
}

//--------------------------------------
//		make rows
//--------------------------------------	
public function makeRows(rows:Number,rowHeight:Number):void {
	
		var cols:int = 1;
		var h:Number = rowHeight;
	 	
      var canvas:Canvas = new Canvas();
      canvas.graphics.beginFill(0xfbfbfb);
      canvas.graphics.drawRect(0,0,markup.width,markup.height);
      canvas.graphics.endFill();
      canvas.x = lines.width ;
      oddLines.addChild(canvas);
        
		//odd lines  
		for(var i:uint = 0; i < rows; i++) {
		    for(var j:uint = 0; j < cols; j++) {
				trace("odd "+i+" - "+j);
				canvas= new Canvas();
		        canvas.graphics.beginFill(0xFFFFFF);
		        canvas.graphics.drawRect(0,0,markup.width,h);
		        canvas.graphics.endFill();
		        canvas.x = lines.width ;
		  		canvas.y =(i * (h*2));
		
		       
		        canvas.addEventListener(MouseEvent.MOUSE_OVER, markLine);
		        canvas.tabEnabled = true;
		        oddLines.addChild(canvas);
		      	}
			}
		

		
		 	 
	    // draw background because textfieldbackground is transparant and
        // ... we dont want to see the clear source.
	    
	      canvas = new Canvas();
	      canvas.graphics.beginFill(0xFFFFFF);
	      canvas.graphics.drawRect(0,0,markup.width,markup.height);
	      canvas.graphics.endFill();
	      canvas.x = lines.width ;
		  evenLines.addChild(canvas);
		  
		//even lines (colors switched) //
		for(i = 0; i < rows; i++) {
		    for(j = 0; j < cols; j++) {
		    	
		    	trace("even "+i+" - "+j);
		       	canvas= new Canvas();
		        canvas.graphics.beginFill(0xfbfbfb);
		        canvas.graphics.drawRect(0,0,markup.width,h);
		        canvas.graphics.endFill();
		        canvas.x = lines.width ;
		  		canvas.y =(i * (h*2));
		  		
		        canvas.addEventListener(MouseEvent.MOUSE_OVER, markLine);
		        canvas.tabEnabled = true;
		        evenLines.addChild(canvas);
		        
		    	}
		    	
			}
	

		
	}	
}



}