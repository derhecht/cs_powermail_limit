<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "cs_powermail_limit".
 *
 * Auto generated | Identifier: af41f9c5d84923c24f9dd436c7d665de
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array(
    'title' => 'Powermail Limit',
    'description' => 'Extends Powermail with limitation functionallity.',
    'category' => 'plugin',
    'version' => '0.0.2',
    'state' => 'beta',
    'uploadfolder' => 0,
    'createDirs' => '',
    'clearcacheonload' => 0,
    'author' => 'Christian Strunk, Jakob Berlin',
    'author_email' => 'christian.m.strunk@googlemail.com, j.berlin@ewerk.com',
    'author_company' => 'EWERK IT GmbH',
    'constraints' =>
        array(
            'depends' =>
                array(
                    'typo3' => '>=7.6',
                    'powermail' => '>=2.25.2'
                ),
            'conflicts' =>
                array(),
            'suggests' =>
                array(),
        ),
    '_md5_values_when_last_written' => 'a:32:{s:21:"ext_conf_template.txt";s:4:"15e1";s:12:"ext_icon.gif";s:4:"e922";s:17:"ext_localconf.php";s:4:"2ba3";s:14:"ext_tables.php";s:4:"df51";s:14:"ext_tables.sql";s:4:"86c5";s:10:"Readme.rst";s:4:"f13b";s:17:"Classes/Limit.php";s:4:"f864";s:39:"Configuration/FlexForms/flexform_ds.xml";s:4:"cc4e";s:38:"Configuration/TypoScript/Constants.txt";s:4:"d41d";s:37:"Documentation/_IncludedDirectives.rst";s:4:"53e5";s:37:"Documentation/AdministratorManual.rst";s:4:"2562";s:33:"Documentation/DeveloperCorner.rst";s:4:"e805";s:23:"Documentation/Index.rst";s:4:"20d3";s:36:"Documentation/ProjectInformation.rst";s:4:"152a";s:38:"Documentation/RestructuredtextHelp.rst";s:4:"c86e";s:37:"Documentation/TyposcriptReference.rst";s:4:"5829";s:28:"Documentation/UserManual.rst";s:4:"3308";s:44:"Documentation/Images/IntroductionPackage.png";s:4:"cdeb";s:30:"Documentation/Images/Typo3.png";s:4:"4fac";s:61:"Documentation/Images/AdministratorManual/ExtensionManager.png";s:4:"14fc";s:47:"Documentation/Images/UserManual/BackendView.png";s:4:"ba6c";s:32:"Documentation/_De/UserManual.rst";s:4:"82b7";s:51:"Documentation/_De/Images/UserManual/BackendView.png";s:4:"ba6c";s:32:"Documentation/_Fr/UserManual.rst";s:4:"56b8";s:51:"Documentation/_Fr/Images/UserManual/BackendView.png";s:4:"ba6c";s:27:"Resources/Private/.htaccess";s:4:"371a";s:40:"Resources/Private/Language/locallang.xlf";s:4:"3213";s:43:"Resources/Private/Language/locallang_db.xlf";s:4:"e33b";s:38:"Resources/Private/Layouts/Default.html";s:4:"d63d";s:37:"Resources/Private/Templates/Main.html";s:4:"a551";s:35:"Resources/Public/Icons/relation.gif";s:4:"e615";s:14:"doc/manual.sxw";s:4:"44da";}',
);

?>