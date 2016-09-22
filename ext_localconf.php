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

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'CW.' . $_EXTKEY,
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