<?php
/***************************************************************
*  Copyright notice
*
*  (c) 1999-2006 Kasper Skaarhoj (kasperYYYY@typo3.com)
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*  A copy is found in the textfile GPL.txt and important notices to the license
*  from the author is found in LICENSE.txt distributed with these scripts.
*
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
 * Include file extending t3lib_recordList
 *
 * $Id: class.file_list.inc 3354 2008-03-03 14:37:46Z ingorenner $
 * Revised for TYPO3 3.6 2/2003 by Kasper Skaarhoj
 *
 * @author	Kasper Skaarhoj <kasperYYYY@typo3.com>
 */
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   75: class fileList extends t3lib_recordList
 *  112:     function start($path,$pointer,$sort,$sortRev,$clipBoard=0)
 *  133:     function generateList()
 *  146:     function writeTop($path)
 *  204:     function linkClipboardHeaderIcon($string,$table,$cmd,$warning='')
 *  218:     function getTable($files,$dirs,$rowlist)
 *  306:     function formatDirList($items)
 *  372:     function linkWrapDir($code,$path)
 *  391:     function linkWrapFile($code,$path)
 *  406:     function listURL()
 *  416:     function dirData($theFile)
 *  441:     function formatFileList($items)
 *  518:     function isImage($ext)
 *  530:     function linkWrapSort($code,$path,$col)
 *  548:     function readDirectory($path,$type,$extList='')
 *  602:     function makeClip($theData)
 *  647:     function makeRef($theData)
 *
 * TOTAL FUNCTIONS: 16
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */






/**
 * Class for rendering of File>Filelist
 *
 * @author	Kasper Skaarhoj <kasperYYYY@typo3.com>
 * @package TYPO3
 * @subpackage core
 */
class fileList extends t3lib_recordList {
	var $iLimit = 40;					// default Max items shown
	var $thumbs = 0;					// Boolean. Thumbnails on records containing files (pictures)
	var $widthGif = '<img src="clear.gif" width="1" height="1" hspace="165" alt="" />';
	var $fixedL = 30;					// Max length of strings
	var $script = 'file_list.php';
	var $clickMenus=1;					// If true click menus are generated on files and folders

	var $sort = '';						// The field to sort by...
	var $sortRev = 1;					// Reverse sorting flag
	var $firstElementNumber=0;
	var $clipBoard = 0;

		// internal
	var $JScode = '';
	var $HTMLcode = '';
	var $totalbytes=0;
	var $dirs = Array();
	var $files = Array();
	var $path = '';
	var $eCounter=0;					// Counting the elements no matter what...
	var $totalItems='';

	var $CBnames=array();



	/**
	 * Initialization of class
	 *
	 * @param	string		The path to list
	 * @param	integer		Pointer
	 * @param	boolean		Sorting column
	 * @param	boolean		Sorting direction
	 * @param	boolean		Show clipboard flag
	 * @return	void
	 */
	function start($path,$pointer,$sort,$sortRev,$clipBoard=0)	{
		$this->counter=0;
		$this->totalbytes=0;
		$this->JScode='';
		$this->HTMLcode='';
		$this->path = $path;
		$this->sort=$sort;
		$this->sortRev=$sortRev;
		$this->firstElementNumber=$pointer;
		$this->clipBoard = $clipBoard;
			// setting the maximum length of the filenames to the user's settings or minimum 30 (= $this->fixedL)
		$this->fixedL = max($this->fixedL, $GLOBALS['BE_USER']->uc['titleLen']); 

		if (!$GLOBALS['TYPO3_CONF_VARS']['GFX']['thumbnails'])	{
			$this->thumbScript='gfx/notfound_thumb.gif';
		}
	}

	/**
	 * Reading files and directories, counting elements and generating the list in ->HTMLcode
	 *
	 * @return	void
	 */
	function generateList()	{
		$this->dirs = $this->readDirectory($this->path,'dir,link');
		$this->files = $this->readDirectory($this->path,'file');
		$this->totalItems=count($this->dirs['sorting'])+count($this->files['sorting']);
		$this->HTMLcode.=$this->getTable($this->files, $this->dirs, 'fileext,tstamp,size,rw,_REF_');
	}

	/**
	 * Return the buttons used by the file list to include in the top header
	 *
	 * @return	array
	 */
	function getButtonsAndOtherMarkers($path) {
		
			// Makes the code for the foldericon in the top
		$path = $GLOBALS['SOBE']->basicFF->is_directory($path);	// Cleaning name...

		if ($path)	{
			$otherMarkers = array(
				'PAGE_ICON' => '',
				'TITLE' => '',
			);
			
			$buttons = array(
				'level_up' => '',
				'refresh' => '',
				'title' => '',
				'page_icon' => '',
			);
			
			$theFile = $GLOBALS['SOBE']->basicFF->getTotalFileInfo($path);

			list($title,$icon,$path) =  $this->dirData($theFile);

				// Start compiling the HTML
			$title = $GLOBALS['SOBE']->basicFF->blindPath($path);

				// if this is some subpage under the mount root....
			if ($GLOBALS['SOBE']->basicFF->checkPathAgainstMounts($theFile['path'])) {
					// The icon with link
				$otherMarkers['PAGE_ICON'] = '<img'.t3lib_iconWorks::skinImg($this->backPath,$icon,'width="18" height="16"').' title="'.htmlspecialchars($theFile['file']).'" alt="" />';
				if ($this->clickMenus) $otherMarkers['PAGE_ICON'] = $GLOBALS['SOBE']->doc->wrapClickMenuOnIcon($otherMarkers['PAGE_ICON'],$path);

				$buttons['level_up'] .= $this->linkWrapDir('<img'.t3lib_iconWorks::skinImg($this->backPath,'gfx/i/folder_up.gif','width="18" height="16"').' title="'.$GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_core.php:labels.upOneLevel',1).'" alt="" />',$theFile['path']);
				$otherMarkers['TITLE'] .= t3lib_div::fixed_lgd_cs($title,-($this->fixedL+20));	// No HTML specialchars here - HTML like <b> </b> is allowed

				// this is the root page
			} else {
				$otherMarkers['PAGE_ICON'] = '<img'.t3lib_iconWorks::skinImg($this->backPath,'gfx/i/_icon_ftp.gif','width="18" height="16"').' alt="" />';
				if ($this->clickMenus) $otherMarkers['PAGE_ICON'] = $GLOBALS['SOBE']->doc->wrapClickMenuOnIcon($otherMarkers['PAGE_ICON'],$path);
				$otherMarkers['TITLE'] .= htmlspecialchars(t3lib_div::fixed_lgd_cs($title,-($this->fixedL+20)));
			}
		}
		
		$buttons['refresh'] = '<a href="'.htmlspecialchars($this->listURL()).'">'.
				'<img'.t3lib_iconWorks::skinImg($this->backPath,'gfx/refresh_n.gif','width="14" height="14"').' title="'.$GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_core.php:labels.reload',1).'" alt="" />'.
				'</a>';
				
		return array($buttons, $otherMarkers);
	}

	/**
	 * Wrapping input string in a link with clipboard command.
	 *
	 * @param	string		String to be linked - must be htmlspecialchar'ed / prepared before.
	 * @param	string		table - NOT USED
	 * @param	string		"cmd" value
	 * @param	string		Warning for JS confirm message
	 * @return	string		Linked string
	 */
	function linkClipboardHeaderIcon($string,$table,$cmd,$warning='')	{
		$onClickEvent = 'document.dblistForm.cmd.value=\''.$cmd.'\';document.dblistForm.submit();';
		if ($warning)	$onClickEvent = 'if (confirm('.$GLOBALS['LANG']->JScharCode($warning).')){'.$onClickEvent.'}';
		return '<a href="#" onclick="'.htmlspecialchars($onClickEvent).'return false;">'.$string.'</a>';
	}

	/**
	 * Returns a table with directories and files listed.
	 *
	 * @param	array		Array of files from path
	 * @param	array		Array of directories from path
	 * @param	string		List of rows to display horizontallyh
	 * @return	string		HTML-table
	 */
	function getTable($files,$dirs,$rowlist)	{
			// Adds the code of files/dirs
		$out='';
		$titleCol = 'file';

			// Cleaning rowlist for duplicates and place the $titleCol as the first column always!
		$rowlist = t3lib_div::rmFromList($titleCol,$rowlist);
		$rowlist = t3lib_div::uniqueList($rowlist);
		$rowlist = $rowlist ? $titleCol.','.$rowlist : $titleCol;
		if ($this->clipBoard)	$rowlist.=',_CLIPBOARD_';
		$this->fieldArray = explode(',',$rowlist);

			// Directories are added
		$iOut = '';
		$iOut.= $this->formatDirList($dirs);

		if (count($dirs['sorting']))	{
				// half line is drawn
			$theData = Array();
			$theData[$titleCol] = '';
			$iOut.=$this->addelement(0,'',$theData);
		}



			// Files are added
		$iOut.= $this->formatFileList($files,$titleCol);

			// Header line is drawn
		$theData = Array();
		reset($this->fieldArray);
		while(list(,$v)=each($this->fieldArray))	{
			if ($v=='_CLIPBOARD_')	{
				$cells=array();
				$table='_FILE';
				$elFromTable = $this->clipObj->elFromTable($table);
				if (count($elFromTable))	{
					$cells[]='<a href="'.htmlspecialchars($this->clipObj->pasteUrl('_FILE',$this->path)).'" onclick="return '.htmlspecialchars($this->clipObj->confirmMsg('_FILE',$this->path,'into',$elFromTable)).'">'.
						'<img'.t3lib_iconWorks::skinImg('','gfx/clip_pasteafter.gif','width="12" height="12"').' title="'.$GLOBALS['LANG']->getLL('clip_paste',1).'" alt="" /></a>';
				}
				if ($this->clipObj->current!='normal' && $iOut)	{
					$cells[]=$this->linkClipboardHeaderIcon('<img'.t3lib_iconWorks::skinImg('','gfx/clip_copy.gif','width="12" height="12"').' title="'.$GLOBALS['LANG']->getLL('clip_selectMarked',1).'" alt="" />',$table,'setCB');
					$cells[]=$this->linkClipboardHeaderIcon('<img'.t3lib_iconWorks::skinImg('','gfx/garbage.gif','width="11" height="12"').' title="'.$GLOBALS['LANG']->getLL('clip_deleteMarked',1).'" alt="" />',$table,'delete',$GLOBALS['LANG']->getLL('clip_deleteMarkedWarning'));
					$onClick = 'checkOffCB(\''.implode(',',$this->CBnames).'\'); return false;';
					$cells[]='<a href="#" onclick="'.htmlspecialchars($onClick).'">'.
							'<img'.t3lib_iconWorks::skinImg('','gfx/clip_select.gif','width="12" height="12"').' title="'.$GLOBALS['LANG']->getLL('clip_markRecords',1).'" alt="" />'.
							'</a>';
				}

				$theData[$v] = implode('',$cells);
			} else {	// Normal row:
				$theT = $this->linkWrapSort($GLOBALS['LANG']->getLL('c_'.$v,1), $this->path,$v);
				$theData[$v] = $theT;
			}
		}
		$out.=$this->addelement(1,'',$theData,' class="c-headLine"','');
		$out.=$iOut;

			// half line is drawn

			// finish
		return '


		<!--
			File list table:
		-->
			<table border="0" cellpadding="0" cellspacing="0" id="typo3-filelist">
				'.$out.'
			</table>';
	}

	/**
	 * Gets the number of files and total size of a folder
	 *
	 * @return string
	 **/
	function getFolderInfo() {
		if($this->counter == 1) {
			$fileLabel = 'File';	
		} else {
			$fileLabel = 'Files';	
		}
		return $this->counter.' '.$fileLabel.', '.t3lib_div::formatSize($this->totalbytes).'bytes';
	}

	/**
	 * This returns tablerows for the directories in the array $items['sorting'].
	 *
	 * @param	array		Directory items
	 * @return	string		HTML table rows.
	 */
	function formatDirList($items)	{
		$out='';

			// Files:
		if (count($items['sorting']))	{
			reset($items['sorting']);
			while (list($key,) = each($items['sorting']))	{
				list($flag,$code) = $this->fwd_rwd_nav();
				$out.=$code;
				if ($flag)	{
						// Initialization
					$theFile = $items['files'][$key];
					$this->counter++;

					list($title,$icon,$path) =  $this->dirData($theFile);

						// The icon with link
					$theIcon = '<img'.t3lib_iconWorks::skinImg($this->backPath,$icon,'width="18" height="16"').' title="'.htmlspecialchars($theFile['file']).'" alt="" />';
					if ($this->clickMenus) $theIcon = $GLOBALS['SOBE']->doc->wrapClickMenuOnIcon($theIcon,$path);

						// 	Preparing and getting the data-array
					$theData = Array();
					reset($this->fieldArray);
					while(list(,$field)=each($this->fieldArray))	{
						switch($field)	{
							case 'size':
	//							$theData[$field]=t3lib_div::formatSize($theFile[$field]);
							break;
							case 'rw':
								$theData[$field]= (($theFile['readable'])?'':'<span class="typo3-red"><b>R</b></span>').(($theFile['writable'])?'':'<span class="typo3-red"><b>W</b></span>');
							break;
							case 'fileext':
	//							$theData[$field]=strtoupper($theFile['fileext']);
							break;
							case 'tstamp':
								$theData[$field]=Date($GLOBALS['TYPO3_CONF_VARS']['SYS']['ddmmyy'], $theFile['tstamp']);
							break;
							case 'file':
								$theData[$field]= $this->linkWrapDir($title,$path);
							break;
							case '_CLIPBOARD_':
								$theData[$field]=$this->makeClip($theFile);
							break;
							case '_REF_':
								$theData[$field]=$this->makeRef($theFile);
							break;
							default:
								$theData[$field]=t3lib_div::fixed_lgd($theFile[$field],$this->fixedL);
							break;
						}
					}
					$out.=$this->addelement(1,$theIcon,$theData);
				}
				$this->eCounter++;
			}
		}
		return $out;
	}

	/**
	 * Wraps the directory-titles
	 *
	 * @param	string		String to be wrapped in links
	 * @param	string		ID (path)
	 * @return	string		HTML
	 */
	function linkWrapDir($code,$path)	{
		$href = $this->script.'?id='.rawurlencode($path);
		$onclick = ' onclick="'.htmlspecialchars('top.content.nav_frame.hilight_row("file","folder'.t3lib_div::md5int($path).'_"+top.fsMod.currentBank)').'"';

			// Sometimes $code contains plain HTML tags. In such a case the string should not be modified!
		if(!strcmp($code,strip_tags($code)))	{
			return '<a href="'.htmlspecialchars($href).'"'.$onclick.' title="'.htmlspecialchars($code).'">'.t3lib_div::fixed_lgd($code,$this->fixedL).'</a>';
		} else	{
			return '<a href="'.htmlspecialchars($href).'"'.$onclick.'>'.$code.'</a>';
		}
	}

	/**
	 * Wraps filenames in links which opens them in a window IF they are in web-path.
	 *
	 * @param	string		String to be wrapped in links
	 * @param	string		ID (path)
	 * @return	string		HTML
	 */
	function linkWrapFile($code,$path)	{

		if (t3lib_div::isFirstPartOfStr($path,t3lib_div::getIndpEnv('TYPO3_DOCUMENT_ROOT')))	{
			$href = substr($path,strlen(t3lib_div::getIndpEnv('TYPO3_DOCUMENT_ROOT')));
			$aOnClick = "return top.openUrlInWindow('".$href."','WebFile');";
			$code = '<a href="#" title="'.htmlspecialchars($code).'" onclick="'.htmlspecialchars($aOnClick).'">'.t3lib_div::fixed_lgd($code,$this->fixedL).'</a>';
		}
		return $code;
	}

	/**
	 * Returns list URL; This is the URL of the current script with id and imagemode parameters, thats all.
	 *
	 * @return	string		URL
	 */
	function listURL()	{
		return $this->script.'?id='.rawurlencode($this->path).'&imagemode='.$this->thumbs;
	}

	/**
	 * Returns some data specific for the directories...
	 *
	 * @param	array		File information array
	 * @return	array		(title, icon, path)
	 */
	function dirData($theFile)	{
		$path = $theFile['path'].$theFile['file'].'/';
		$webpath=t3lib_BEfunc::getPathType_web_nonweb($path);

		$title=htmlspecialchars($theFile['file']);
		$icon = 'gfx/i/_icon_'.$webpath.'folders.gif';
		if ($title=='_temp_')	{
			$icon = 'gfx/i/sysf.gif';
			$title='<b>TEMP</b>';
		}
		if ($title=='_recycler_')	{
			$icon = 'gfx/i/recycler.gif';
			$title='<b>RECYCLER</b>';
		}
		$icon = $theFile['writable'] ? 'gfx/i/_icon_'.$webpath.'folders_ro.gif' : $icon;

		return Array($title,$icon,$path);
	}

	/**
	 * This returns tablerows for the files in the array $items['sorting'].
	 *
	 * @param	array		File items
	 * @return	string		HTML table rows.
	 */
	function formatFileList($items)	{
		$out='';

			// Files:
		if (count($items['sorting']))	{
			reset($items['sorting']);
			while (list($key,) = each($items['sorting']))	{
				list($flag,$code) = $this->fwd_rwd_nav();
				$out.=$code;
				if ($flag)	{
						// Initialization
					$theFile = $items['files'][$key];
					$this->counter++;

					$this->totalbytes+=$theFile['size'];
					$ext = $items['files'][$key][fileext];
					$icon = t3lib_BEfunc::getFileIcon($ext);

						// The icon with link
					$theIcon = '<img'.t3lib_iconWorks::skinImg($this->backPath,'gfx/fileicons/'.$icon,'width="18" height="16"').' title="'.htmlspecialchars($theFile['file']).'" alt="" />';
					if ($this->clickMenus) $theIcon = $GLOBALS['SOBE']->doc->wrapClickMenuOnIcon($theIcon,$theFile['path'].$theFile['file']);

						// 	Preparing and getting the data-array
					$theData = Array();
					reset($this->fieldArray);
					while(list(,$field)=each($this->fieldArray))	{
						switch($field)	{
							case 'size':
								$theData[$field]=t3lib_div::formatSize($theFile[$field]);
							break;
							case 'rw':
								$theData[$field]= (($theFile['readable'])?'':'<span class="typo3-red"><b>R</b></span>').(($theFile['writable'])?'':'<span class="typo3-red"><b>W</b></span>');
							break;
							case 'fileext':
								$theData[$field]=strtoupper($theFile[$field]);
							break;
							case 'tstamp':
								$theData[$field]=Date($GLOBALS['TYPO3_CONF_VARS']['SYS']['ddmmyy'], $theFile[$field]);
							break;
							case '_CLIPBOARD_':
								$theData[$field]=$this->makeClip($theFile);
							break;
							case '_REF_':
								$theData[$field]=$this->makeRef($theFile);
							break;
							case 'file':
								$theData[$field] = $this->linkWrapFile($theFile[$field],$theFile['path'].$theFile['file']);
							break;
							default:
								$theData[$field]=t3lib_div::fixed_lgd($theFile[$field],$this->fixedL);
							break;
						}
					}
					$out.= $this->addelement(1,$theIcon,$theData);

						// Thumbsnails?
					if ($this->thumbs && $this->isImage($theFile['fileext']))	{
						$thumbData = array();
						$theFile_R = rawurlencode($theFile['path'].$theFile['file']);
						$titleCol = $this->fieldArray[0];

						$theFile_abs = $theFile['path'].$theFile['file'];
						$check = basename($theFile_abs).':'.filemtime($theFile_abs).':'.$GLOBALS['TYPO3_CONF_VARS']['SYS']['encryptionKey'];
						$params = '&file='.$theFile_R;
						$params.= '&md5sum='.t3lib_div::shortMD5($check);
						$href = $this->backPath.$this->thumbScript.'?&dummy='.$GLOBALS['EXEC_TIME'].$params;

						$thumbData[$titleCol]='<img src="'.htmlspecialchars($href).'" hspace="2" title="'.htmlspecialchars(trim($theFile['file'])).'" alt="" />';
						$out.= $this->addelement(4,'',$thumbData);
					}
				}
				$this->eCounter++;
			}
		}
		return $out;
	}

	/**
	 * Returns true if $ext is an image-extension according to $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
	 *
	 * @param	string		file extension
	 * @return	boolean
	 */
	function isImage($ext)	{
		return t3lib_div::inList($GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'],strtolower($ext));
	}

	/**
	 * Wraps the directory-titles ($code) in a link to file_list.php (id=$path) and sorting commands...
	 *
	 * @param	string		String to be wrapped
	 * @param	string		ID (path)
	 * @param	string		Sorting column
	 * @return	string		HTML
	 */
	function linkWrapSort($code,$path,$col)	{
		if ($this->sort==$col && !$this->sortRev)	{		// reverse sorting
			$params='&SET[sort]='.$col.'&SET[reverse]=1';
		} else {
			$params='&SET[sort]='.$col.'&SET[reverse]=0';
		}
		$href = $this->script.'?id='.rawurlencode($path).$params;
		return '<a href="'.htmlspecialchars($href).'">'.$code.'</a>';
	}

	/**
	 * Returns an array with file/dir items + an array with the sorted items
	 *
	 * @param	string		Path (absolute) to read
	 * @param	string		$type is the technical type; file,dir,link. empty is all kinds of stuff.
	 * @param	string		$extList: List of fileextensions to select. If empty, all are selected.
	 * @return	array		Array('files'=>array(), 'sorting'=>array());
	 */
	function readDirectory($path,$type,$extList='')	{
		$items = Array('files'=>array(), 'sorting'=>array());
		$path = $GLOBALS['SOBE']->basicFF->is_directory($path);	// Cleaning name...

		if($path && $GLOBALS['SOBE']->basicFF->checkPathAgainstMounts($path.'/'))	{
			$d = @dir($path);
			$tempArray=Array();
			if (is_object($d))	{
				while($entry=$d->read()) {
					if ($entry!='.' && $entry!='..')	{
						$wholePath = $path.'/'.$entry;		// Because of odd PHP-error where  <br />-tag is sometimes placed after a filename!!
						if (@file_exists($wholePath) && (!$type || t3lib_div::inList($type,filetype($wholePath))))	{
							if ($extList)	{
								$fI = t3lib_div::split_fileref($entry);
								if (t3lib_div::inList($extList,$fI['fileext']))	{
									$tempArray[] = $wholePath;
								}
							} else {
								$tempArray[] = $wholePath;
							}
						}
					}
				}
				$d->close();
			}
				// Get fileinfo
			reset($tempArray);
			while (list(,$val)=each($tempArray))	{
				$temp = $GLOBALS['SOBE']->basicFF->getTotalFileInfo($val);
				$items['files'][] = $temp;
				if ($this->sort)	{
					$items['sorting'][] = strtoupper($temp[$this->sort]);
				} else {
					$items['sorting'][] = '';
				}
			}
				// Sort if required
			if ($this->sort)	{
				if (!$this->sortRev)	{
					asort($items['sorting']);
				} else {
					arsort($items['sorting']);
				}
			}
		}
		return $items;
	}

	/**
	 * Creates the clipboard control pad
	 *
	 * @param	array		Array with information about the file/directory for which to make the clipboard panel for the listing.
	 * @return	string		HTML-table
	 */
	function makeClip($theData)	{
		$cells=array();
		$fullIdent = $theData['path'].$theData['file'];
		$md5=t3lib_div::shortmd5($fullIdent);

			// For normal clipboard, add copy/cut buttons:
		if ($this->clipObj->current=='normal')	{
			$isSel = $this->clipObj->isSelected('_FILE',$md5);
			$cells[]='<a href="'.htmlspecialchars($this->clipObj->selUrlFile($fullIdent,1,($isSel=='copy'))).'">'.
						'<img'.t3lib_iconWorks::skinImg('','gfx/clip_copy'.($isSel=='copy'?'_h':'').'.gif','width="12" height="12"').' title="'.$GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_core.php:cm.copy',1).'" alt="" />'.
						'</a>';
			$cells[]='<a href="'.htmlspecialchars($this->clipObj->selUrlFile($fullIdent,0,($isSel=='cut'))).'">'.
						'<img'.t3lib_iconWorks::skinImg('','gfx/clip_cut'.($isSel=='cut'?'_h':'').'.gif','width="12" height="12"').' title="'.$GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_core.php:cm.cut',1).'" alt="" />'.
						'</a>';
		} else {	// For numeric pads, add select checkboxes:
			$n='_FILE|'.$md5;
			$this->CBnames[]=$n;

			$checked = ($this->clipObj->isSelected('_FILE',$md5)?' checked="checked"':'');
			$cells[]='<input type="hidden" name="CBH['.$n.']" value="0" />'.
					'<input type="checkbox" name="CBC['.$n.']" value="'.htmlspecialchars($fullIdent).'" class="smallCheckboxes"'.$checked.' />';
		}

			// Display PASTE button, if directory:
		$elFromTable = $this->clipObj->elFromTable('_FILE');
		if (@is_dir($fullIdent) && count($elFromTable))	{
			$cells[]='<a href="'.htmlspecialchars($this->clipObj->pasteUrl('_FILE',$fullIdent)).'" onclick="return '.htmlspecialchars($this->clipObj->confirmMsg('_FILE',$fullIdent,'into',$elFromTable)).'">'.
						'<img'.t3lib_iconWorks::skinImg('','gfx/clip_pasteinto.gif','width="12" height="12"').' title="'.$GLOBALS['LANG']->getLL('clip_pasteInto',1).'" alt="" />'.
						'</a>';
		}

			// Compile items into a DIV-element:
		return '							<!-- CLIPBOARD PANEL: -->
											<div class="typo3-clipCtrl">
												'.implode('
												',$cells).'
											</div>';
	}

	/**
	 * Make reference count
	 *
	 * @param	array		Array with information about the file/directory for which to make the clipboard panel for the listing.
	 * @return	string		HTML
	 */
	function makeRef($theData)	{

			// First, fit path to match what is stored in the refindex:
		$fullIdent = $theData['path'].$theData['file'];

		if (t3lib_div::isFirstPartOfStr($fullIdent,PATH_site))	{
			$fullIdent = substr($fullIdent,strlen(PATH_site));
		}

			// Look up the path:
		$rows = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
			'*',
			'sys_refindex',
			'ref_table='.$GLOBALS['TYPO3_DB']->fullQuoteStr('_FILE','sys_refindex').
				' AND ref_string='.$GLOBALS['TYPO3_DB']->fullQuoteStr($fullIdent,'sys_refindex').
				' AND deleted=0'
		);

			// Compile information for title tag:
		$infoData=array();
		if (is_array($rows)) {
			foreach($rows as $row)	{
				$infoData[]=$row['tablename'].':'.$row['recuid'].':'.$row['field'];
			}
		}

		return count($infoData) ? '<a href="#" onclick="'.htmlspecialchars('top.launchView(\''.$theData['path'].$theData['file'].'\', \'\'); return false;').'" title="'.htmlspecialchars(t3lib_div::fixed_lgd(implode(' / ',$infoData),100)).'">'.count($infoData).'</a>' : '';
	}
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['typo3/class.file_list.inc'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['typo3/class.file_list.inc']);
}
?>
