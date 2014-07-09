<?php
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

/**
 *
 *
 * @package cw_twitter
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class Tx_CwTwitter_Controller_TweetController extends Tx_Extbase_MVC_Controller_ActionController {

	/**
	 * @param Tx_Extbase_MVC_View_ViewInterface $view
	 * @return void
	 */
	protected function initializeView(Tx_Extbase_MVC_View_ViewInterface $view) {
		$view->assign('contentObjectData', $this->configurationManager->getContentObject()->data);
	}

	/**
	 * List tweets
	 *
	 * @return void
	 */
	public function listAction() {
		try {
			$tweets = Tx_CwTwitter_Utility_Twitter::getTweetsFromSettings($this->settings);
			if($this->settings['mode'] == 'timeline') {
				$this->view->assign('user', Tx_CwTwitter_Utility_Twitter::getUserFromSettings($this->settings));
			}
		}
		catch(Tx_CwTwitter_Exception_ConfigurationException $e) {
			return $e->getMessage();
		}
		catch(Tx_CwTwitter_Exception_RequestException $e) {
			t3lib_div::sysLog($e->getMessage(), 'cw_twitter', 3);
			$this->view->assign('error', $e);
		}

		$this->view->assignMultiple(array(
			'tweets' => $tweets,
		));
	}
}
?>