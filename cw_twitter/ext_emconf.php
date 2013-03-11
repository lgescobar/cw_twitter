<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "cw_twitter".
 *
 * Auto generated 11-03-2013 12:20
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Twitter feed',
	'description' => 'Show tweets from user or search query on frontend. Compatible with the new Twitter API (1.1).',
	'category' => 'plugin',
	'author' => 'Arjan de Pooter',
	'author_email' => 'arjan@cmsworks.nl',
	'author_company' => 'CMS Works',
	'shy' => '',
	'priority' => '',
	'module' => '',
	'state' => 'beta',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'version' => '1.1.2',
	'constraints' => array(
		'depends' => array(
			'extbase' => '1.3',
			'fluid' => '1.3',
			'typo3' => '4.5-0.0.0',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:24:{s:9:"ChangeLog";s:4:"2416";s:21:"ext_conf_template.txt";s:4:"d20c";s:12:"ext_icon.gif";s:4:"e7c7";s:17:"ext_localconf.php";s:4:"a36e";s:14:"ext_tables.php";s:4:"f862";s:14:"ext_tables.sql";s:4:"6701";s:25:"Classes/Contrib/OAuth.php";s:4:"2f29";s:38:"Classes/Controller/TweetController.php";s:4:"2e5a";s:44:"Classes/Exception/ConfigurationException.php";s:4:"e1bd";s:38:"Classes/Exception/RequestException.php";s:4:"542a";s:27:"Classes/Utility/Twitter.php";s:4:"db45";s:46:"Classes/ViewHelpers/Format/TweetViewHelper.php";s:4:"f33e";s:44:"Classes/ViewHelpers/Link/TweetViewHelper.php";s:4:"7bb0";s:53:"Classes/ViewHelpers/Link/Tweet/AbstractViewHelper.php";s:4:"3329";s:53:"Classes/ViewHelpers/Link/Tweet/FavoriteViewHelper.php";s:4:"bbd6";s:50:"Classes/ViewHelpers/Link/Tweet/ReplyViewHelper.php";s:4:"ae25";s:52:"Classes/ViewHelpers/Link/Tweet/RetweetViewHelper.php";s:4:"e466";s:39:"Configuration/FlexForms/TwitterFeed.xml";s:4:"1276";s:38:"Configuration/TypoScript/constants.txt";s:4:"8045";s:34:"Configuration/TypoScript/setup.txt";s:4:"fca9";s:40:"Resources/Private/Language/locallang.xml";s:4:"52d8";s:43:"Resources/Private/Language/locallang_db.xml";s:4:"41e4";s:38:"Resources/Private/Layouts/Default.html";s:4:"0025";s:43:"Resources/Private/Templates/Tweet/List.html";s:4:"dd05";}',
);

?>