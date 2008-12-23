/**
* ...
* @author Default
* @version 0.1
*/

package as3editor.outliner
{

	public final class Parse
	{
		/**
		 * Regexp for matching package pattern 
		 */
		static public const RXP_PACKAGE:RegExp = /\b(package\s+)\b([^\{]*)?(\{(\w|\W)*[\}])/;
		public static const RIDX_PACKAGE_PATH:uint  = 2;
		public static const RIDX_PACKAGE_BODY:uint  = 3;
		//
		public static const RXP_IMPORT_PATH:RegExp = /\b(import\s+)\b(.)*/g;
		//                                     //ACCESS                                     //TYPE                                     //CLASS   //NAME  //EXTENDS            //IMPLEMENTS                //BODY        
		public static const RXP_CLASS:RegExp = /\b(public\s+|private\s+|static\s+|final\s+)?(public\s+|private\s+|static\s+|final\s+)?(class\s+)(\w*)((\s+|extends|implements|\w|\.|,)*)?\b               (\s*\{)?/gx;
		public static const RIDX_CLASS_ACCES:uint     = 1;
		public static const RIDX_CLASS_ACCES2:uint     = 2;
		public static const RIDX_CLASS_TYPE:uint      = 3;
		public static const RIDX_CLASS_NAME:uint      = 4;
		public static const RIDX_CLASS_EXTENDS_IMPLEMENTS:uint= 5;
		public static const RIDX_CLASS_LEFTBRACE:uint = 7;
		
		//                                              //[0]ACCESS                                                               //[2] i-path static override final                                      //[3] function var const                                           //[4] name     //[5] function-head | var-type                          //[7] function body
		public static const RXP_CLASS_ENTITIES:RegExp = /\b (private\s+|public\s+|protected\s+|static\s+|override\s+|final\s+)?  (static\s+|override\s+|final\s+|private\s+|public\s+|protected\s+)?      (function\s+set\s+|function\s+get\s+|function\s+|var\s+|const\s+)  (\w*) \b       ( \s*\([^\(\)]*\) (\s*:\s*\w+)? | : \s* \w{1,20} )?     (\s*\{)?  /gx;
		public static const RIDX_CLASS_ENTITY_ACCES:uint = 0;
		public static const RIDX_CLASS_ENTITY_ACCES2:uint = 2;
		public static const RIDX_CLASS_ENTITY_TYPE:uint   = 3;
		public static const RIDX_CLASS_ENTITY_NAME:uint   = 4;
		public static const RIDX_CLASS_ENTITY_FUNCTION_HEAD:uint = 5;
		public static const RIDX_CLASS_ENTITY_VAR_TYPE:uint      = 5;
		public static const RIDX_FUNCTION_RETURNTYPE:uint        = 6;
		public static const RIDX_FUNCTION_LEFT_BRACE:uint        = 7;
		
		public static const BRACE_LEFT:RegExp  = /{/m;
		public static const BRACE_RIGHT:RegExp = /}/m;
		
		public static const RXP_IMPORT:RegExp = /\bimport\b/ ;
		public static const RXP_SETTER:RegExp = /\bfunction\s+set\b/ ;
		public static const RXP_GETTER:RegExp = /\bfunction\s+get\b/ ;
		public static const RXP_FUNCTION:RegExp = /\bfunction\b/ ;
		public static const RXP_VAR:RegExp      = /\bvar\b/ ;
		public static const RXP_CONST:RegExp    = /\bconst\b/ ;
		
		public static const RXP_JAVADOC_G:RegExp = /(\/\*\*(\s*|.*?)*\*\/)((\s*|.*?)*(function|var|const|package|class))(\s+\w*)/g;
		public static const RXP_JAVADOC:RegExp   = /(\/\*\*(\s*|.*?)*\*\/)((\s*|.*?)*(function|var|const|package|class))(\s+\w*)/;
		public static const RIDX_JDOC_NAME:uint = 6;
		public static const RIDX_JDOC_TYPE:uint = 5;
		
		
		public static const RXP_COMMENT_G:RegExp = /(\/\*(\s*|.*?)*\*\/)|(\/\/.*)/g;
		public static const RXP_COMMENT:RegExp   = /(\/\*(\s*|.*?)*\*\/)|(\/\/.*)/;
		
		public static const RXP_QUOTE_G:RegExp  = /(\"(\s*|.*?)\"|\'(\s*|.*?)\')/g;
		public static const RXP_QUOTE:RegExp    = /(\"(\s*|.*?)\"|\'(\s*|.*?)\')/;
		
		public static const RXP_REGEXP_G:RegExp = /\/([^;\n]*?)\//g;
		public static const RXP_REGEXP:RegExp   = /\/([^;\n]*?)\//;
		//
		
		public static const RXP_ACCES:RegExp    = /\b(public|private|protected)\b/;
		public static const RXP_ACCES2:RegExp   = /\b(static|override|final)\b/;
		
		/**
		 * Returns charIndex of the counter brace of '{' in the source-code
		 * @param	charindex
		 * @param	source
		 * @return
		 */
		public static function getRigthCounterBraceIndex( charindex:int, source:String ):int
		{
			var braces:RegExp  = /(\{|\})/g;
			braces.lastIndex = charindex + 1;
			var checka:Array = braces.exec( source );
			var foundIndex:int;
			//this function assumes the first unmatched left-brace was found at charindex
			var unmatchedleftbraces:int=1;
			
			while( unmatchedleftbraces > 0 && unmatchedleftbraces < 500 )
			{
				if( checka )
				{
					if( checka[0]=="{" )
					{
						unmatchedleftbraces ++;
					}
					else if( checka[0]=="}" )
					{
						unmatchedleftbraces --;
						foundIndex = checka.index;
					}
				}
				checka  = braces.exec( source );
			}
			return foundIndex+1;
		}
		
		/**
		 * Returns charIndex of the counter brace of '}' in the source-code
		 * @param	charindex
		 * @param	source
		 * @return
		 */
		public static function getLeftCounterBraceIndex( charindex:int, source:String ):int
		{
			var src:String = source.slice( 0, charindex-1 ).split("").reverse().join();
			var regleft:RegExp  = /{/gm;
			var regright:RegExp = /}/gm;
			var resleft:Array = new Array();
			var resright:Array = new Array();
			var foundIndex:int;
			//this function assumes the first unmatched right-brace was found at charindex
			var unmatchedrightbraces:uint = 1;
			
			while ( unmatchedrightbraces > 0 )
			{
					resleft  = regleft.exec( src );
					resright = regright.exec( src );
					if ( resleft )
					{
						unmatchedrightbraces --;
						foundIndex = resleft.index;
					}
					if ( resright )
						unmatchedrightbraces ++;
			}
			return charindex-( src.length-foundIndex )+1;
		}
		
		/**
		 * Strips comments from an actionscript source-string
		 * @param	source
		 */
		public static function removeComments(source:String):String
		{
			var r:Array = new Array();
			return source.replace(Parse.RXP_COMMENT_G, "\n");
		}
		
		/**
		 * Strips quotes from an actionscript source-string
		 * @param	source
		 * @return
		 */
		public static function removeQuotes(source:String):String
		{
			var r:Array = new Array();
			return source.replace(Parse.RXP_QUOTE_G, "_quote_");
		}
		
		/**
		 * Strips regexp's from an actionscript source-string
		 * @param	source
		 * @return
		 */
		public static function removeRegExps(source:String):String
		{
			var r:Array = new Array();
			return source.replace(Parse.RXP_REGEXP_G, "_regexp_");
		}
		
	}
}
