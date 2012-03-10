<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2012 Christian Strunk <Christian.M.Strunk@googlemail.com>
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
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 * Hint: use extdeveval to insert/update function index above.
 */

require_once(PATH_tslib.'class.tslib_pibase.php');


/**
 * Plugin '' for the 'cs_powermail_limit' extension.
 *
 * @author	Christian Strunk <Christian.M.Strunk@googlemail.com>
 * @package	TYPO3
 * @subpackage	tx_cspowermaillimit
 */
class tx_cspowermaillimit_pi1 extends tslib_pibase {
	var $prefixId      = 'tx_cspowermaillimit_pi1';		// Same as class name
	var $scriptRelPath = 'pi1/class.tx_cspowermaillimit_pi1.php';	// Path to this script relative to the extension dir.
	var $extKey        = 'cs_powermail_limit';	// The extension key.
	var $pi_checkCHash = true;
	
	/**
	 * PM_MainContentAfterHook
	 *
	 * @param	string		$content: The PlugIn content
	 * @param	array		$piVars: The PlugIn piVars
	 * @param	array		$caller: The PlugIn $this
	 * @return	The content that is displayed on the website
	 */
	
	
	function PM_MainContentAfterHook(&$content, &$piVars, &$caller) {
		// get config and flexform values
		$extconf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][cs_powermail_limit]);
		$caller->pi_initPIflexform("tx_cspowermaillimit_pi_flexform");
		// set flexform
		$flexform = $caller->cObj->data['tx_cspowermaillimit_pi_flexform'];
		// maillimits
		$maillimit 			= $caller->pi_getFFvalue($flexform, 'Mail-Limit', 'sMaillimit'); 			// Limit for Mails 
		$contentID 			= $caller->pi_getFFvalue($flexform, 'Mail-LimitDBrecord', 'sMaillimit');	// possible ContentID (DB record) Maillimit
		$content_msg 		= $caller->pi_getFFvalue($flexform, 'Mail-LimitRTEmessage', 'sMaillimit');	// possible Content (RTE Message) Maillimit
		// startlimits
		$startlimit 		= $caller->pi_getFFvalue($flexform, 'Start-date', 'sStarttime');			// Limit for startdate
		$startlimit_time	= $caller->pi_getFFvalue($flexform, 'Start-time', 'sStarttime');			// Limit for starttime			
		$contentID_start 	= $caller->pi_getFFvalue($flexform, 'Start-DBrecord', 'sStarttime');		// possible ContentID for startdate
		$content_start_msg 	= $caller->pi_getFFvalue($flexform, 'Start-RTEmessage', 'sStarttime');		// possible Content (RTE Message) Maillimit
		// endlimits
		$endlimit 			= $caller->pi_getFFvalue($flexform, 'End-date', 'sEndtime');				// Limit for Enddate
		$endlimit_time 		= $caller->pi_getFFvalue($flexform, 'End-time', 'sEndtime');				// Limit for Endtime
		$contentID_end 		= $caller->pi_getFFvalue($flexform, 'End-DBrecord', 'sEndtime');			// possible ContentID for enddate
		$content_end_msg 	= $caller->pi_getFFvalue($flexform, 'End-RTEmessage', 'sStarttime');		// possible Content (RTE Message) Maillimit
		
		// standard Content
		$stdContent = $caller->pi_RTEcssText($extconf['standardText']);
		
		 // Lets beginn ... Check the Limit
		if ( $maillimit > 0 || $startlimit != 0 || $endlimit != 0 ) { 		 
			
			// Set some Vars
			$startlimit_exceeded = 1;
			$maillimit_exceeded = 0;
			$endlimit_exceeded = 0;
			$msg = 0; 
			$timezoneOffset = $extconf['timezoneOffset']*3600;
	
			// startlimit
			if ( $startlimit != 0 )  {
				// get date of today
				$today = time() + $timezoneOffset;
				$timelimit = $startlimit + $startlimit_time;
			
				if ($today >= $timelimit) {
					$startlimit_exceeded = 1;
				}
				else {
					$startlimit_exceeded = 0;
					// set content
					if ($content_start_msg != "") {
						$cid = $content_start_msg;
						$msg = 1;
					}
					else {
						$cid = $contentID_start;	
					}
				}
			}
			
			// Maillimit
			if ( $maillimit != 0 && $startlimit_exceeded == 1 ) {
				// 	Get the Mailcount
				$table = 'tx_powermail_mails';
				$where = 'formid=' .$caller->cObj->data[uid] .$caller->cObj->enableFields($table);
				$groupBy = '';
				$orderBy = '';
				$limit = '';
				$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery (
					'*',
					$table,
					$where,
					$groupBy,
					$orderBy,
					$limit
				);
				$mailcount = $GLOBALS['TYPO3_DB']->sql_num_rows($res);
				
				if ($mailcount >= $maillimit) {
					$maillimit_exceeded = 1;
					if ($content_msg != "") {
						$cid = $content_msg;
						$msg = 1;
					}
					else {
						$cid = $contentID;	
					}
				}
				else {
					$maillimit_exceeded = 0;
				}	
			}
			
			// Endlimit			
			if ( $endlimit != 0 && $startlimit_exceeded == 1 && $maillimit_exceeded == 0 ) {
				// get date of today
				$today = time() + $timezoneOffset;
				$timelimit = $endlimit + $endlimit_time;
				
				if ($today < $timelimit) {
					$endlimit_exceeded = 0;
				}
				else {
					$endlimit_exceeded = 1;
					if ($content_end_msg != "") {
						$cid = $content_end_msg;
						$msg = 1;
					}
					else {
						$cid = $contentID_end;	
					}
				}
			}
			
			// check if any limit is reached
			if (($maillimit_exceeded == 1 || $startlimit_exceeded == 0 || $endlimit_exceeded == 1) && empty($piVars['sendNow'])) {
				// show limit message or content
				if ( $cid != "" ) {
					if ($msg == 0) {
						$tt_content_conf = array('tables' => 'tt_content','source' => $cid,'dontCheckPid' => 1);
						$content = $caller->cObj->RECORDS($tt_content_conf);
					}
					else {
						$content = $caller->pi_RTEcssText($cid);;
					} 
				}
				else {
					$content = $stdContent;
				}
			} else {
				// powermail content ist displayed without modification
			}
		}
	}
	
	/**
	 * PM_FieldHook
	 * @param	string		$xml: The fields xml date
	 * @param	string		$type: The fields type
	 * @param	int			$uid: The uid of the field
	 * @param	array		$markerArray: MarkerArray 
	 * @param	array		$piVarsFromSession: pIVars of Sessions
	 * @param	array		$caller: $this
	 * @return	The content that is displayed on the website
	 */
	
	// Field for Information about the Form
	public function PM_FieldHook(&$xml, &$title, &$type, &$uid, &$markerArray, &$piVarsFromSession, &$caller) {
		//config
		$caller->xml = $xml; // this is the flexform for the field
		$this->pi_loadLL();
		$extconf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][cs_powermail_limit]);
		
		//get FlexForm Values
		$caller->pi_initPIflexform("tx_cspowermaillimit_pi_flexform");
		$flexform = $caller->cObj->data['tx_cspowermaillimit_pi_flexform'];
		$dateFormat = $extconf['dateFormat'];
		$timeFormat = $extconf['timeFormat'];
		// checkbox
		$bitcount = $caller->pi_getFFvalue(t3lib_div::xml2array($xml),'showMail-Limit');
		$bit = decbin($bitcount);
		for ($i=0; $i<4; $i++) {
			$arr[$i] =  (($bitcount >> $i) & 1) ? 1 : 0;
		} 
		
		// startlimit
		if ($arr[0] == 1) {
			$title = $this->pi_getLL('tx_cspowermaillimit.powermail.field.limitinformation.startTime.label');
			$startlimit 	 = $caller->pi_getFFvalue($flexform, 'Start-date', 'sStarttime');
			$startlimit_time = $caller->pi_getFFvalue($flexform, 'Start-time', 'sStarttime');
			$timeSpacer = $this->pi_getLL('tx_cspowermaillimit.powermail.field.limitinformation.timeSpacer');
	
			if ($startlimit != 0) {
				$startlimit = date($dateFormat, $startlimit);
				if ($startlimit_time != 0) {
					$startlimit_time = date($timeFormat,$startlimit_time);
					$time = $startlimit. $timeSpacer .$startlimit_time; 
				}
				else {
					$starttime = $startlimit;
				} 
			} 
			else {
				$starttime = $this->pi_getLL('tx_cspowermaillimit.powermail.field.limitinformation.noStartlimit');
			}
			
			$content .= '<div class="Starttime"><label class="Starttime">'.$title.'</label><span class="freePlaces">'.$starttime.'</span></div>';
		}

		// end limit
		if ($arr[1] == 1) {
			$title = $this->pi_getLL('tx_cspowermaillimit.powermail.field.limitinformation.endTime.label');	
			$endlimit 		= $caller->pi_getFFvalue($flexform, 'End-date', 'sEndtime');
			$endlimit_time 	= $caller->pi_getFFvalue($flexform, 'End-time', 'sEndtime');
			$timeSpacer = $this->pi_getLL('tx_cspowermaillimit.powermail.field.limitinformation.timeSpacer');
			
			if ($endlimit != 0) {
				$endlimit = date($dateFormat, $endlimit);
				if ($endlimit_time != 0) {
					$endlimit_time = date($timeFormat, $endlimit_time); 
					$endtime = $endlimit. $timeSpacer .$endlimit_time;
				}
				else {
					$endtime = $endlimit;	
				} 
			} 
			else {
				$endtime = $this->pi_getLL('tx_cspowermaillimit.powermail.field.limitinformation.noEndlimit');
			}
			
			$content .= '<div class="endTime"><label class="endtime">'.$title.'</label><span class="freePlaces">'.$endtime.'</span></div>';
		}

		// Places
		if ($arr[2] == 1) {	
			$title = $this->pi_getLL('tx_cspowermaillimit.powermail.field.limitinformation.places.label');
			$places 	= ($caller->pi_getFFvalue($flexform, 'Mail-Limit', 'sMaillimit') != "") ? $caller->pi_getFFvalue($flexform, 'Mail-Limit', 'sMaillimit') : $this->pi_getLL('tx_cspowermaillimit.powermail.field.limitinformation.noLimit.label');
			$content .= '<div class="paces"><label class="Starttime">'.$title.'</label><span class="freePlaces">'.$places.'</span></div>';
		}

		// free places
		if ($arr[3] == 1) {	
			$title = $this->pi_getLL('tx_cspowermaillimit.powermail.field.limitinformation.freePlaces.label');
			$maillimit 	= $caller->pi_getFFvalue($flexform, 'Mail-Limit', 'sMaillimit'); // Limit for Mails
					
			// get freePlaces
			$table = 'tx_powermail_mails';
			$where = 'formid=' .$caller->cObj->data['uid'] .$caller->cObj->enableFields($table);
			$groupBy = '';
			$orderBy = '';
			$limit = '';
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery (
				'*',
				$table,
				$where,
				$groupBy,
				$orderBy,
				$limit
			);
			$mailcount = $GLOBALS['TYPO3_DB']->sql_num_rows($res);
					
			// calculate free places
			$freePlaces = ($maillimit - $mailcount < 0) ? "0": $maillimit - $mailcount;
					
			$content .= '<div class="freePlaces"><label class="Starttime">'.$title.'</label><span class="freePlaces">'.$freePlaces.'</span></div>';
		}	
		return $content;
	}
	
	
//end of main - function	
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/cs_powermail_limit/pi1/class.tx_cspowermaillimit_pi1.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/cs_powermail_limit/pi1/class.tx_cspowermaillimit_pi1.php']);
}

?>