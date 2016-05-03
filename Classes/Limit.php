<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Christian Strunk <christian.m.strunk@googlemail.com>
 *  
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
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
 *
 *
 * @package cs_powermail_limit2
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
 
class Tx_CsPowermailLimit2_Limit extends Tx_Powermail_Controller_FormsController { 
    
  function checkLimit($forms, $caller) {
        
        // get cObjects...
        $cObj = $this->configurationManager->getContentObject();
        $pid = $cObj->data['pid'];
        
        // Config holen
       	$extconf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['cs_powermail2_limit']);

        // maillimits
        $maillimit             	= $this->settings['flexform']['mail']['maillimit'];     // Limit for Mails 
        $contentID             	= $this->settings['flexform']['mail']['dbrecord'];      // possible ContentID (DB record) Maillimit
        $content_msg        	= $this->settings['flexform']['mail']['message'];       // possible Content (RTE Message) Maillimit
        // Timelimit
        $timeslot            	= $this->settings['flexform']['deleteTempMessages'];    // Timeslot
        // startlimits
        $startlimit         	= $this->settings['flexform']['start']['startdate'];    // Limit for startdate
        $startlimit_time    	= $this->settings['flexform']['start']['starttime'];    // Limit for starttime            
        $contentID_start     	= $this->settings['flexform']['start']['dbrecord'];     // possible ContentID for startdate
        $content_start_msg     	= $this->settings['flexform']['start']['message'];      // possible Content (RTE Message) Maillimit
        // endlimits
        $endlimit             	= $this->settings['flexform']['end']['enddate'];        // Limit for Enddate
        $endlimit_time         	= $this->settings['flexform']['end']['endtime'];        // Limit for Endtime
        $contentID_end         	= $this->settings['flexform']['end']['dbrecord'];       // possible ContentID for enddate
        $content_end_msg     	= $this->settings['flexform']['end']['message'];        // possible Content (RTE Message) Maillimit
        
        // standard Content
        $standardContentMail    = $extconf['standardContentMail'];
        $standardContentStart   = $extconf['standardContentStart'];
        $standardContentEnd     = $extconf['standardContentEnd'];

       	// if ( ($maillimit > 0 && $maillimit != "" ) || ($startlimit != 0 && $startlimit != "")  || ($endlimit != 0 && $endlimit != "") ) {          
        if ( $maillimit > 0 || $startlimit != 0 || $endlimit != 0) {  
            // set status to zero
            $status = 0;
            
            // startlimit
            if ( $startlimit != 0 )  {
                // get date of today
                $today = time();
                if ($today < $startlimit+$startlimit_time) { $status = "1"; }
            }
            
            // Maillimit
            if ( $maillimit != 0 && $status != "1" ) {
                
                // check temp Mails and delete old mails...
                if ($timeslot != "" && $timeslot != "0" ) {    
                    $this->deleteOldTempMails($pid, $timeslot);    
                }
                
                //  Get the Mailcount
                $table = 'tx_powermail_domain_model_mails';
                $where = 'pid=' .$pid .$cObj->enableFields($table);
                $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery ('*', $table, $where);
                $dbMailCount = $GLOBALS['TYPO3_DB']->sql_num_rows($res);
                
				// Get TempMails (if enabled)
				if ($timeslot != "" && $timeslot != "0" ) {
                	$table = 'tx_cspowermaillimit2_temp_mails';
					$where = "";
                	$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery ('*',$table,$where);
                	$tempMailCount = $GLOBALS['TYPO3_DB']->sql_num_rows($res);
				} 
				else {
					$tempMailCount = 0;
				}
                
                //get Full Mail count
                $mailcount = $dbMailCount + $tempMailCount;
                
                if ($mailcount >= $maillimit) {
                    if ($status != "1") { $status = "2"; }
                } else {
                    if ($timeslot != "" && $timeslot != "0" ) {    
                        // set temp_mail
                        $this->setTempMail($pid);
                    }
                }     
            }
            
            // Endlimit            
            if ( $endlimit != 0 ) {
                // get date of today
                $today = time();
                if ($today > $endlimit+$endlimit_time) {
                    $status = "3";
                }
            }
        }
        
        // set contents
        if ( $status != 0 ) {
            // set Templates
            $caller->view->setTemplatePathAndFilename('typo3conf/ext/cs_powermail_limit2/Resources/Private/Templates/Main.html');
            $caller->view->setLayoutRootPath('typo3conf/ext/cs_powermail_limit2/Resources/Private/Layouts/');
            $caller->view->setPartialRootPath('typo3conf/ext/cs_powermail_limit2/Resources/Private/Partials/');
            
            switch ($status) {
                case '1':
                    if ($contentID_start != "" || $content_start_msg != "" ) {
                        if ($content_start_msg != "") {
                            $content = $content_start_msg; 
                        } 
                        else {
                            $tt_content_conf = array('tables' => 'tt_content','source' => $contentID_start,'dontCheckPid' => 1);
                            $content = $cObj->RECORDS($tt_content_conf);
                        }
                    }
                    else {
                        $content = $standardContentMail;
                    } 
                    break;
                case '2':
                    if ($contentID != "" || $content_msg != "" ) {
                        if ($content_msg != "") {
                            $content = $content_msg; 
                        } 
                        else {
                            $tt_content_conf = array('tables' => 'tt_content','source' => $contentID,'dontCheckPid' => 1);
                            $content = $cObj->RECORDS($tt_content_conf);
                        }
                    }
                    else {
                        $content = $standardContentStart;
                    } 
                    break;
                case '3':
                    if ($contentID_end != "" || $content_end_msg != "" ) {
                        if ($content_end_msg != "") {
                            $content = $content_end_msg; 
                        } 
                        else {
                            $tt_content_conf = array('tables' => 'tt_content','source' => $contentID_end,'dontCheckPid' => 1);
                            $content = $cObj->RECORDS($tt_content_conf);
                        }
                    }
                    else {
                        $content = $standardContentEnd;
                    } 
                    break;
                default:
                    $content = "Error! - Something went wrong while getting the conent...";
                    break;
            }
			// Assign Template
        	$caller->view->assign('content', $content);
        	$caller->view->render();
        }
        
    }

    private function setTempMail($pid) {
        $insertArray = array(
            'uid' => '',
            'crdate' => time(),
            'pid' => $pid
        ); 
        $query = $GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_cspowermaillimit2_temp_mails', $insertArray);
        //$res = $GLOBALS['TYPO3_DB']->sql_query(TYPO3_db, $query);
    }
    
    private function deleteOldTempMails($pid, $timeslot) {
        // get Time from timeslot...
        $time = time() - ($timeslot * 60);

        // delete older rows... 
        $query = $GLOBALS['TYPO3_DB']->exec_DELETEquery("tx_cspowermaillimit2_temp_mails", "crdate < " . $time . " AND pid = " . $pid);
        //$res = $GLOBALS['TYPO3_DB']->sql_query(TYPO3_db, $query);
    }
	 
	 
}

?>