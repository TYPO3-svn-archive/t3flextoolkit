package as3editor.editor.lang
{
	import flash.text.TextFormat;
	
	import mx.core.UITextField;
	
	public class Actionscript3HighlightingDefinitions
	{
		public var Lang:Object;
		public var LangEnabled:Object;
		
		public function getLang():Object {
			
			var enabledElements:Array=new Array();
			for each(var o:Object in Lang.syntax) {
				if (o.enabled==true) {
					enabledElements.push(o);
				}
			}
			return {syntax : enabledElements};
		}
		
		public function process(t:UITextField,begin:int,end:int):void {
				var currentFormat:TextFormat=new TextFormat();
				currentFormat.color=0x111111;
			
				t.defaultTextFormat=currentFormat;
				
				t.replaceText(begin,end,t.getRawText().slice(begin,end));
				processPatterns(t,begin,end);
				
		}
		public function processInBackground(t:UITextField,scanRow:int):void {
			if ((scanRow+1) < t.numLines) {
					var currentFormat:TextFormat=new TextFormat();
					currentFormat.color=0x111111;
					t.defaultTextFormat=currentFormat;
					var b:int=t.getLineOffset(scanRow);
					var e:int=t.getLineOffset((scanRow)+1);
					processPatterns(t,b,e);
				} 
		}
				
				
		
		
		public function processPatterns(t:UITextField,begin:int,end:int):void {
			
			var documentPart:String=t.text.slice(begin,end);
			var i:int;
				for each(var s:Object in LangEnabled.syntax) {
				
					var r:RegExp=s.match;
					var matches:Array=r.exec(documentPart);
					var currentFormat:TextFormat=new TextFormat();
				
					currentFormat.color=s.color;
					currentFormat.bold=s.bold;
					currentFormat.italic=s.italic;
					
					while (matches != null)
						{
	    
					    t.setTextFormat(currentFormat,matches.index+begin, r.lastIndex+begin);
						matches = r.exec(documentPart);
					
					
					} 

				
			}
			
		}
		
		public function Actionscript3HighlightingDefinitions()
		{
			
			Lang = {
				syntax : [
				//--------------------------------------
				//		imports
				//--------------------------------------	
				{
				context:"imports",
				match: /\b(import\s+)\b(.)*/g,
				color: 0x111111,
				bold : false,
				italic : false,
				enabled:true
				},
				//--------------------------------------
				//		keywords
				//--------------------------------------	
				{
				context:"keywords",
				match: /\b(true|false|abstract|continue|for|new|switch|assert|default|goto|synchronized|boolean|do|if|private|this|break|double|implements|protected|throw|byte|else|import|public|throws|case|enum|instanceof|return|transient|catch|extends|int|short|try|char|final|static|void|finally|long|strictfp|volatile|const|float|native|super|while)\b/g,
				color: 0x0033FF,
				bold : false,
				italic : false,
				enabled:true
				}, 
				
				//--------------------------------------
				//		function
				//--------------------------------------	
				{
				context:"function",
				match: /\b(function)\b/g,
				color: 0x339966,
				bold : false,
				italic : false,
				enabled:true
				},
				//--------------------------------------
				//		package interface class
				//--------------------------------------
				{
				context:"package",	
				match: /\b(package|interface|class)\b/g,
				color: 0x9900CC,
				bold : false,
				italic : false,
				enabled:true
				},			
				//--------------------------------------
				//		variables VAR
				//--------------------------------------	
				{
				context:"var",
				match: /\b(var)\b/g,
				color: 0x6699CC,
				bold : false,
				italic : false,
				enabled:true
				},			
				//--------------------------------------
				//		comment blocks
				//--------------------------------------	
				{
				context:"comment blocks",
				match: new RegExp('(/\\*[\\s\\S]*?\\*/)','g'),
				color: 0x3f5fbf,
				bold : false,
				italic : true,
				enabled:false
				},
				//--------------------------------------
				//		comment single line
				//--------------------------------------	
				{
				context:"comment single line",
				match : new RegExp('//.*$', 'gm'),
				color: 0x009900,
				bold : false,
				italic : false,
				enabled:false
				},
				//--------------------------------------
				//		string double quotes
				//--------------------------------------	
				{
				context:"string double quotes",
				match : new RegExp('"(?:[^"\n]|[\"])*?".*?','gm'),
				color: 0x990000,
				bold : false,
				italic : false,
				enabled:true
				},
				//--------------------------------------
				//		string single quotes
				//--------------------------------------	
				{
				context:"string single quotes",
				match : new RegExp("'(?:[^'\n]|[\'])*?'.*?",'gm'),
				color: 0x990000,
				bold : false,
				italic : false,
				enabled:true
				},
				//--------------------------------------
				//		typo3 template elements
				//--------------------------------------			
				{
				context:"typo3 template elements",
				match: "",
				color: 0x990000,
				bold : false,
				italic : false,
				enabled:false
				},
				//--------------------------------------
				//		url's
				//--------------------------------------		
				{
				context:"url's",
				match: "",
				color: "#990000",
				bold : false,
				italic : false,
				enabled:false
				}
				]			
				}	
				
				
				LangEnabled=getLang();
					
			}
	}
}