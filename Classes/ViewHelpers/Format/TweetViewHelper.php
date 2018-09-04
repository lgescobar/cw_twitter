<?php
namespace CW\CwTwitter\ViewHelpers\Format;

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

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3Fluid\Fluid\Core\ViewHelper\Exception;

/**
 *
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class TweetViewHelper extends AbstractViewHelper
{
    /**
     * This ViewHelper needs to render unescaped children to get the entities to be parsed.
     *
     * @var bool
     */
    protected $escapeChildren = false;

    /**
     * As this ViewHelper renders HTML, the output must not be escaped.
     *
     * @var bool
     */
    protected $escapeOutput = false;

    /**
     * @var array
     */
    protected $typoScriptSetup;

    /**
     * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface
     */
    protected $configurationManager;

    /**
     * @param \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface $configurationManager
     * @return void
     */
    public function injectConfigurationManager(ConfigurationManagerInterface $configurationManager)
    {
        $this->configurationManager = $configurationManager;
        $this->typoScriptSetup = $this->configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT
        );
    }

    /**
     * Format the entities (hashtags, urls, usermentions, media) in the tweet
     *
     * @param array $tweet The tweet object
     * @param string $urlParser TypoScript path for parsing urls
     * @param string $hashtagParser TypoScript path for parsing urls
     * @param string $mentionParser TypoScript path for parsing urls
     * @param string $mediaParser TypoScript path for parsing urls
     * @return string
     */
    public function render(
        $tweet = [],
        $urlParser = 'plugin.tx_cwtwitter.parsers.urls',
        $hashtagParser = 'plugin.tx_cwtwitter.parsers.hashtags',
        $mentionParser = 'plugin.tx_cwtwitter.parsers.mentions',
        $mediaParser = 'plugin.tx_cwtwitter.parsers.media'
    ) {
        if (empty($tweet)) {
            $tweet = $this->renderChildren();
        }

        if (isset($tweet['full_text'])) {
            $tweettext = $tweet['full_text'];
        } elseif (isset($tweet['text'])) {
            $tweettext = $tweet['text'];
        } else {
            throw new Exception("Tweet object doesn't contain text property.", 1362042983);
        }

        if (isset($tweet['entities'])) {
            $entityTypes = [
                'hashtags' => $hashtagParser,
                'urls' => $urlParser,
                'user_mentions' => $mentionParser,
                'media' => $mediaParser,
            ];

            $replacements = [];
            foreach ($entityTypes as $type => $parsePath) {
                if (isset($tweet['entities'][$type])
                    && is_array($tweet['entities'][$type])
                    && !empty($tweet['entities'][$type])
                ) {
                    foreach ($tweet['entities'][$type] as $entity) {
                        list($start, $stop) = $entity['indices'];
                        $replacements[$start] = [
                            'text' => $this->getDataFromParser($parsePath, $entity),
                            'width' => $stop - $start,
                        ];
                    }
                }
            }

            krsort($replacements);
            foreach ($replacements as $start => $replacement) {
                $tweettext = mb_substr($tweettext, 0, $start, 'UTF-8')
                    . $replacement['text']
                    . mb_substr($tweettext, $start + $replacement['width'], mb_strlen($tweettext, 'UTF-8'), 'UTF-8');
            }

            return $tweettext;
        }
    }

    /**
     * @param string $path
     * @return array
     */
    protected function getTypoScriptObject($path)
    {
        $setup = $this->typoScriptSetup;
        $segments = explode('.', $path);
        $lastSegment = array_pop($segments);

        foreach ($segments as $segment) {
            if (isset($setup[$segment . '.'])) {
                $setup = $setup[$segment . '.'];
            } else {
                throw new Exception(
                    'TypoScript object path "' . htmlspecialchars($path) . '" does not exist',
                    1362046927
                );
            }
        }

        return [
            $setup[$lastSegment],
            $setup[$lastSegment . '.'],
        ];
    }

    /**
     * @param string $path Path to the TS-object
     * @param array $data The data to use
     * @return string
     */
    protected function getDataFromParser($path, array $data)
    {
        list($type, $tsObj) = $this->getTypoScriptObject($path);

        $contentObject = GeneralUtility::makeInstance(ContentObjectRenderer::class);
        $contentObject->start($data);

        return $contentObject->cObjGetSingle($type, $tsObj);
    }
}
