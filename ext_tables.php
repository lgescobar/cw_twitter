<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'CW.' . $_EXTKEY,
	'Pi1',
	'Twitter feed'
);

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['cwtwitter_pi1'] = 'select_key,recursive,pages';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['cwtwitter_pi1'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue('cwtwitter_pi1', 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/TwitterFeed.xml');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Twitter feed');

?>