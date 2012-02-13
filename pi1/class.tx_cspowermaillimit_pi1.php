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
 * Plugin 'powermail-limit' for the 'cs_powermail_limit' extension.
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
	 * The main method of the PlugIn
	 *
	 * @param	string		$content: The PlugIn content
	 * @param	array		$conf: The PlugIn configuration
	 * @return	The content that is displayed on the website
	 */

	function PM_MainContentAfterHook(&$content, &$piV, &$t) {
		// Config holen
		$extconf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][cs_powermail_limit]);

		// Check for Maillimits and Timelimits
		$table = 'tt_content';
		$where = 'uid='.$t->cObj->data[uid] .$t->cObj->enableFields($table);
		$groupBy = '';
		$orderBy = 'sorting';
		$limit = '';
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery (
			'*',
		$table,
		$where,
		$groupBy,
		$orderBy,
		$limit
		);
		$rows = array();
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res))  {
			$rows[] = $row;
		}
		$GLOBALS['TYPO3_DB']->sql_free_result($res);

		// fill some vars...
		$maillimit = $rows[0][tx_cspowermaillimit_limit]; 				// Limit for Mails
		$startlimit = $rows[0][tx_cspowermaillimit_starttime];			// Limit for startdate
		$endlimit = $rows[0][tx_cspowermaillimit_enddate];				// Limit for Enddate
		$contentID = $rows[0][tx_cspowermaillimit_record];				// possible ContentID for enddate AND Maillimit
		$contentID_start = $rows[0][tx_cspowermaillimit_record_start];	// possible ContentID for startdate
		$contentID_end = $rows[0][tx_cspowermaillimit_record_end];		// possible ContentID for enddate

		// standard Content
		$stdContent_temp = $extconf['standardText'];
		$stdContent = $t->pi_RTEcssText($stdContent_temp);

		/*
		 * Lets beginn ... Check the Limit
		 */
		if ( $maillimit > 0 || $startlimit != 0 || $endlimit != 0 ) {

			// Set some Vars
			$startlimit_exceeded = 1;
			$maillimit_exceeded = 0;
			$endlimit_exceeded = 0;

			// startlimit
			if ( $startlimit != 0 )  {
				// get date of today
				$today = time();
				if ($today >= $startlimit) {
					$startlimit_exceeded = 1;
				}
				else {
					$startlimit_exceeded = 0;
					// set content
					$cid = $contentID_start;
				}
			}

			// Maillimit
			if ( $maillimit != 0 && $startlimit_exceeded == 1 ) {
				// 	Get the Mailcount
				$table = 'tx_powermail_mails';
				$where = 'formid=' .$t->cObj->data[uid] .$t->cObj->enableFields($table);
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
					$cid = $contentID;
				}
				else {
					$maillimit_exceeded = 0;
				}
			}

			// Endlimit
			if ( $endlimit != 0 && $startlimit_exceeded == 1 && $maillimit_exceeded == 0 ) {
				// get date of today
				$today = time();
				if ($today < $endlimit) {
					$endlimit_exceeded = 0;
				}
				else {
					$endlimit_exceeded = 1;
					$cid = $contentID_end;
				}
			}

			//get content
			if ($maillimit_exceeded == 1 || $startlimit_exceeded == 0 || $endlimit_exceeded == 1) {
				if ( $cid != 0 ) {
					$tt_content_conf = array('tables' => 'tt_content','source' => $cid,'dontCheckPid' => 1);
					$content = $t->cObj->RECORDS($tt_content_conf);
				}
				else {
					$content = $stdContent;
				}
			}
		}
	}

	public function PM_FieldHook(&$xml, &$title, &$type, &$uid, &$markerArray, &$piVarsFromSession, &$caller) {

		// Check for Maillimits and Timelimits
		$table = 'tt_content';
		$where = 'uid='.$caller->cObj->data['uid'] .$caller->cObj->enableFields($table);
		$groupBy = '';
		$orderBy = 'sorting';
		$limit = '';
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery (
			'*',
		$table,
		$where,
		$groupBy,
		$orderBy,
		$limit
		);
		$rows = array();
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res))  {
			$rows[] = $row;
		}
		$GLOBALS['TYPO3_DB']->sql_free_result($res);

		// current limit value
		$maillimit = $rows[0]['tx_cspowermaillimit_limit']; 				// Limit for Mails

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
		$freePlaces = $maillimit - $mailcount;

		$content = '<div class="formRow freePlaces"><label class="freePlaces">'.$title.'</label><span class="freePlaces">'.$freePlaces.'</span></div>';

		return $content;
	}

	//end of function
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/cs_powermail_limit/pi1/class.tx_cspowermaillimit_pi1.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/cs_powermail_limit/pi1/class.tx_cspowermaillimit_pi1.php']);
}

?>