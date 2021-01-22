<?php
defined('TYPO3_MODE') or die('Access denied.');

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Arjan de Pooter <arjan@cmsworks.nl>, CMS Works BV
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
 * ************************************************************* */

call_user_func(function ($extKey) {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'CW.' . $extKey,
        'Pi1',
        [
            'Tweet' => 'list',
        ],
        // non-cacheable actions
        [
            'Tweet' => 'list',
        ]
    );

    if (!is_array($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['cwtwitter_queries'])) {
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['cwtwitter_queries'] = [];
    }

    if (!isset($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['cwtwitter_queries']['frontend'])) {
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['cwtwitter_queries']['frontend'] =
            \TYPO3\CMS\Core\Cache\Frontend\VariableFrontend::class;
    }

    if (!isset($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'][$extKey]['options'])) {
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'][$extKey]['options'] = [
            'defaultLifetime' => 60,
        ];
    }

    // Conditionally load OAuth classes to avoid redefining classes.
    $extPath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($extKey);
    $oAuthClasses = [
        'OAuthException',
        'OAuthConsumer',
        'OAuthToken',
        'OAuthSignatureMethod',
        'OAuthSignatureMethod_HMAC_SHA1',
        'OAuthSignatureMethod_PLAINTEXT',
        'OAuthSignatureMethod_RSA_SHA1',
        'OAuthRequest',
        'OAuthServer',
        'OAuthDataStore',
        'OAuthUtil',
    ];

    foreach ($oAuthClasses as $oAuthClass) {
        if (!class_exists($oAuthClass)) {
            require $extPath . 'Classes/Contrib/' . $oAuthClass . '.php';
        }
    }
}, 'cw_twitter');
