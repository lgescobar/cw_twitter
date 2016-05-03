<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

if (!is_array($TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['cwtwitter_queries'])) {
    $TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['cwtwitter_queries'] = array();
}
if (!isset($TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['cwtwitter_queries']['frontend'])) {
    $TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['cwtwitter_queries']['frontend'] = '\\TYPO3\\CMS\Core\\Cache\\Frontend\\VariableFrontend';
}
if (\TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionNumberToInteger(TYPO3_version) < '4006000') {
    if (!isset($TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['cwtwitter_queries']['backend'])) {
        $TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['cwtwitter_queries']['backend'] = 't3lib_cache_backend_DbBackend';
    }
    if (!isset($TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['cwtwitter_queries']['options'])) {
        $TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['cwtwitter_queries']['options'] = array();
    }
    if (!isset($TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['cwtwitter_queries']['options']['cacheTable'])) {
        $TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['cwtwitter_queries']['options']['cacheTable'] = 'tx_cwtwitter_queries';
    }
    if (!isset($TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['cwtwitter_queries']['options']['tagsTable'])) {
        $TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['cwtwitter_queries']['options']['tagsTable'] = 'tx_cwtwitter_queries_tags';
    }
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	$_EXTKEY,
	'Pi1',
	array(
		'Tweet' => 'list',
	),
	// non-cacheable actions
	array(
		'Tweet' => 'list',
	)
);

?>