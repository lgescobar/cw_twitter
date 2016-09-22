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
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;

/**
 *
 *
 * @package cw_twitter
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class TweetController extends ActionController
{
    /**
     * @param ViewInterface $view
     * @return void
     */
    protected function initializeView($view)
    {
        $view->assign('contentObjectData', $this->configurationManager->getContentObject()->data);
    }

    /**
     * List tweets
     *
     * @return void
     */
    public function listAction()
    {
        try {
            $tweets = Twitter::getTweetsFromSettings($this->settings);
            if ($this->settings['mode'] == 'timeline') {
                $this->view->assign('user', Twitter::getUserFromSettings($this->settings));
            }
        } catch (ConfigurationException $e) {
            return $e->getMessage();
        } catch (RequestException $e) {
            GeneralUtility::sysLog($e->getMessage(), 'cw_twitter', GeneralUtility::SYSLOG_SEVERITY_ERROR);
            $this->view->assign('error', $e);
        }

        $this->view->assignMultiple(array(
            'tweets' => $tweets,
        ));
    }
}

?>