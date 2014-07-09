<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "cw_twitter".
 *
 * Auto generated 22-01-2014 09:44
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array (
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
	'version' => '1.1.8',
	'constraints' =>

	array (
		'depends' =>
		array (
			'extbase' => '1.3',
			'fluid' => '1.3',
			'typo3' => '4.5.0-6.2.99',
		),
		'conflicts' =>
		array (
		),
		'suggests' =>
		array (
		),
	),
	'_md5_values_when_last_written' => 'a:75:{s:9:"ChangeLog";s:4:"2416";s:21:"ext_conf_template.txt";s:4:"d20c";s:12:"ext_icon.gif";s:4:"e7c7";s:17:"ext_localconf.php";s:4:"a36e";s:14:"ext_tables.php";s:4:"f862";s:14:"ext_tables.sql";s:4:"6701";s:25:"Classes/Contrib/OAuth.php";s:4:"2f29";s:38:"Classes/Controller/TweetController.php";s:4:"2e5a";s:44:"Classes/Exception/ConfigurationException.php";s:4:"e1bd";s:38:"Classes/Exception/RequestException.php";s:4:"542a";s:27:"Classes/Utility/Twitter.php";s:4:"db45";s:46:"Classes/ViewHelpers/Format/TweetViewHelper.php";s:4:"f33e";s:44:"Classes/ViewHelpers/Link/TweetViewHelper.php";s:4:"7bb0";s:53:"Classes/ViewHelpers/Link/Tweet/AbstractViewHelper.php";s:4:"3329";s:53:"Classes/ViewHelpers/Link/Tweet/FavoriteViewHelper.php";s:4:"bbd6";s:50:"Classes/ViewHelpers/Link/Tweet/ReplyViewHelper.php";s:4:"ae25";s:52:"Classes/ViewHelpers/Link/Tweet/RetweetViewHelper.php";s:4:"e466";s:39:"Configuration/FlexForms/TwitterFeed.xml";s:4:"e6eb";s:38:"Configuration/TypoScript/constants.txt";s:4:"8045";s:34:"Configuration/TypoScript/setup.txt";s:4:"fca9";s:28:"Documentation/_Inclusion.txt";s:4:"ea0f";s:37:"Documentation/AdministratorManual.rst";s:4:"2a5c";s:23:"Documentation/Index.rst";s:4:"8237";s:26:"Documentation/Settings.yml";s:4:"637b";s:28:"Documentation/UserManual.rst";s:4:"e3c0";s:30:"Documentation/Images/Typo3.png";s:4:"82b7";s:69:"Documentation/Images/AdministratorManual/extmanager_configuration.png";s:4:"a953";s:58:"Documentation/Images/AdministratorManual/twitter_oauth.png";s:4:"0896";s:27:"Documentation/_make/conf.py";s:4:"110c";s:33:"Documentation/_make/make-html.bat";s:4:"6d1c";s:28:"Documentation/_make/make.bat";s:4:"9890";s:28:"Documentation/_make/Makefile";s:4:"6110";s:46:"Documentation/_make/_not_versioned/_.gitignore";s:4:"829c";s:47:"Documentation/_make/_not_versioned/warnings.txt";s:4:"d41d";s:62:"Documentation/_make/build/doctrees/AdministratorManual.doctree";s:4:"c7cf";s:53:"Documentation/_make/build/doctrees/environment.pickle";s:4:"e936";s:48:"Documentation/_make/build/doctrees/Index.doctree";s:4:"1101";s:53:"Documentation/_make/build/doctrees/UserManual.doctree";s:4:"41d8";s:55:"Documentation/_make/build/html/AdministratorManual.html";s:4:"9e1e";s:44:"Documentation/_make/build/html/genindex.html";s:4:"6067";s:41:"Documentation/_make/build/html/Index.html";s:4:"ec0a";s:42:"Documentation/_make/build/html/objects.inv";s:4:"ae1d";s:42:"Documentation/_make/build/html/search.html";s:4:"e9e7";s:45:"Documentation/_make/build/html/searchindex.js";s:4:"b796";s:46:"Documentation/_make/build/html/UserManual.html";s:4:"6ca5";s:67:"Documentation/_make/build/html/_images/extmanager_configuration.png";s:4:"a953";s:56:"Documentation/_make/build/html/_images/twitter_oauth.png";s:4:"0896";s:48:"Documentation/_make/build/html/_images/Typo3.png";s:4:"82b7";s:63:"Documentation/_make/build/html/_sources/AdministratorManual.txt";s:4:"2a5c";s:49:"Documentation/_make/build/html/_sources/Index.txt";s:4:"8237";s:54:"Documentation/_make/build/html/_sources/UserManual.txt";s:4:"e3c0";s:54:"Documentation/_make/build/html/_static/ajax-loader.gif";s:4:"ae66";s:48:"Documentation/_make/build/html/_static/basic.css";s:4:"941d";s:57:"Documentation/_make/build/html/_static/comment-bright.png";s:4:"0c85";s:56:"Documentation/_make/build/html/_static/comment-close.png";s:4:"2635";s:50:"Documentation/_make/build/html/_static/comment.png";s:4:"882e";s:50:"Documentation/_make/build/html/_static/default.css";s:4:"2a6a";s:50:"Documentation/_make/build/html/_static/doctools.js";s:4:"ee0c";s:55:"Documentation/_make/build/html/_static/down-pressed.png";s:4:"ebe8";s:47:"Documentation/_make/build/html/_static/down.png";s:4:"f6f3";s:47:"Documentation/_make/build/html/_static/file.png";s:4:"6587";s:48:"Documentation/_make/build/html/_static/jquery.js";s:4:"ddb8";s:48:"Documentation/_make/build/html/_static/minus.png";s:4:"8d57";s:47:"Documentation/_make/build/html/_static/plus.png";s:4:"0125";s:51:"Documentation/_make/build/html/_static/pygments.css";s:4:"3fe3";s:53:"Documentation/_make/build/html/_static/searchtools.js";s:4:"8314";s:49:"Documentation/_make/build/html/_static/sidebar.js";s:4:"f094";s:52:"Documentation/_make/build/html/_static/underscore.js";s:4:"b538";s:53:"Documentation/_make/build/html/_static/up-pressed.png";s:4:"8ea9";s:45:"Documentation/_make/build/html/_static/up.png";s:4:"ecc3";s:52:"Documentation/_make/build/html/_static/websupport.js";s:4:"55ec";s:40:"Resources/Private/Language/locallang.xml";s:4:"52d8";s:43:"Resources/Private/Language/locallang_db.xml";s:4:"41e4";s:38:"Resources/Private/Layouts/Default.html";s:4:"0025";s:43:"Resources/Private/Templates/Tweet/List.html";s:4:"dd05";}',
);

?>
