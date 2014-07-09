<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

Tx_Extbase_Utility_Extension::registerPlugin(
	$_EXTKEY,
	'Pi1',
	'Twitter feed'
);

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['cwtwitter_pi1'] = 'layout,select_key,recursive';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['cwtwitter_pi1'] = 'pi_flexform';
t3lib_extMgm::addPiFlexFormValue('cwtwitter_pi1', 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/TwitterFeed.xml');

t3lib_extMgm::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Twitter feed');

?>