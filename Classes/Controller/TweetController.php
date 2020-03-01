<?php
namespace CW\CwTwitter\Controller;

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

use CW\CwTwitter\Exception\ConfigurationException;
use CW\CwTwitter\Exception\RequestException;
use CW\CwTwitter\Utility\Twitter;
use Psr\Log\LoggerInterface;
use TYPO3\CMS\Core\Log\LogManager;
use TYPO3\CMS\Core\TypoScript\TypoScriptService;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;

/**
 *
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class TweetController extends ActionController
{
    /**
     * @var \TYPO3\CMS\Core\TypoScript\TypoScriptService
     */
    protected $typoScriptService;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @param \TYPO3\CMS\Core\TypoScript\TypoScriptService $typoScriptService
     */
    public function injectTypoScriptService(TypoScriptService $typoScriptService)
    {
        $this->typoScriptService = $typoScriptService;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Mvc\View\ViewInterface $view
     * @return void
     */
    protected function initializeView(ViewInterface $view)
    {
        $view->assign('contentObjectData', $this->configurationManager->getContentObject()->data);
    }

    /**
     * Override defined settings before calling action methods.
     *
     * @return void
     */
    public function initializeAction()
    {
        if (!empty($this->settings['overrideFlexformSettings'])) {
            $typoScriptSettings = $this->getTypoScriptSettings();
            $keysToOverride = GeneralUtility::trimExplode(',', $this->settings['overrideFlexformSettings'], true);

            foreach ($keysToOverride as $keyToOverride) {
                if (isset($typoScriptSettings[$keyToOverride])) {
                    $this->settings[$keyToOverride] = $typoScriptSettings[$keyToOverride];
                }
            }
        }

        if (!$this->logger instanceof LoggerInterface) {
            $this->logger = GeneralUtility::makeInstance(LogManager::class)->getLogger(__CLASS__);
        }
    }

    /**
     * List tweets
     *
     * @throws \OAuthException
     * @throws \TYPO3\CMS\Core\Cache\Exception\NoSuchCacheException
     */
    public function listAction()
    {
        try {
            $tweets = Twitter::getTweetsFromSettings($this->settings);
            $this->view->assign('tweets', $tweets);
            if ($this->settings['mode'] == 'timeline') {
                $this->view->assign('user', Twitter::getUserFromSettings($this->settings));
            }
        } catch (ConfigurationException $e) {
            return $e->getMessage();
        } catch (RequestException $e) {
            $this->logger->error($e->getMessage());
            $this->view->assign('error', $e);
        }
    }

    /**
     * Helper method to get the plugin settings not overridden by FlexForm settings.
     *
     * @see \TYPO3\CMS\Extbase\Configuration\FrontendConfigurationManager::getPluginConfiguration()
     *
     * @return array
     */
    protected function getTypoScriptSettings()
    {
        $extensionName = $this->extensionName;
        $pluginName = $this->request->getPluginName();
        $pluginSettings = [];

        $setup = $this->configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT
        );

        if (is_array($setup['plugin.']['tx_' . strtolower($extensionName) . '.']['settings.'])) {
            $pluginSettings = $this->typoScriptService->convertTypoScriptArrayToPlainArray(
                $setup['plugin.']['tx_' . strtolower($extensionName) . '.']['settings.']
            );
        }

        if ($pluginName !== null) {
            $pluginSignature = strtolower($extensionName . '_' . $pluginName);
            if (is_array($setup['plugin.']['tx_' . $pluginSignature . '.']['settings.'])) {
                ArrayUtility::mergeRecursiveWithOverrule(
                    $pluginSettings,
                    $this->typoScriptService->convertTypoScriptArrayToPlainArray(
                        $setup['plugin.']['tx_' . $pluginSignature . '.']['settings.']
                    )
                );
            }
        }

        return $pluginSettings;
    }
}
