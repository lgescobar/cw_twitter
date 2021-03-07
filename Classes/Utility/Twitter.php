<?php
namespace CW\CwTwitter\Utility;

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
use CW\CwTwitter\Http\RequestFactory;
use GuzzleHttp\Exception\GuzzleException;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Cache\Frontend\AbstractFrontend;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\VersionNumberUtility;
use TYPO3\CMS\Extensionmanager\Utility\ConfigurationUtility;

/**
 *
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class Twitter
{
    /**
     * @var AbstractFrontend
     */
    protected $cache;

    /**
     * @var array
     */
    protected $oauthParameters = [];

    /**
     * The base api url
     *
     * @var string
     */
    protected $api_url = 'https://api.twitter.com/1.1/';

    /**
     * Construct Twitter-object from settings
     *
     * @param array $settings
     * @return Twitter
     * @throws ConfigurationException
     * @throws \TYPO3\CMS\Core\Cache\Exception\NoSuchCacheException
     */
    public static function getTwitterFromSettings($settings)
    {
        if (!$settings['oauth']['consumer']['key'] || !$settings['oauth']['consumer']['secret']
            || !$settings['oauth']['token']['key'] || !$settings['oauth']['token']['secret']
        ) {
            throw new ConfigurationException('Missing OAuth keys and/or secrets.', 1362059167);
        }

        $twitter = new self();
        $twitter->setConsumer($settings['oauth']['consumer']['key'], $settings['oauth']['consumer']['secret']);
        $twitter->setToken($settings['oauth']['token']['key'], $settings['oauth']['token']['secret']);

        return $twitter;
    }

    /**
     * @param $settings
     * @return array
     * @throws ConfigurationException
     * @throws RequestException
     * @throws \TYPO3\CMS\Core\Cache\Exception\NoSuchCacheException
     */
    public static function getTweetsFromSettings($settings)
    {
        $twitter = self::getTwitterFromSettings($settings);

        $limit = (int)$settings['limit'];
        switch ($settings['mode']) {
            case 'timeline':
                return $twitter->getTweetsFromTimeline(
                    $settings['username'],
                    $limit,
                    $settings['exclude_replies'],
                    $settings['enhanced_privacy'],
                    $settings['extended_tweet_mode'],
                    $settings['include_rts']
                );
                break;
            case 'search':
                return $twitter->getTweetsFromSearch($settings['query'], $limit, $settings['enhanced_privacy']);
                break;
            default:
                throw new ConfigurationException('Invalid mode specified.', 1362059199);
                break;
        }
    }

    /**
     * @param $settings
     * @return array
     * @throws ConfigurationException
     * @throws RequestException
     * @throws \TYPO3\CMS\Core\Cache\Exception\NoSuchCacheException
     */
    public static function getUserFromSettings($settings)
    {
        $twitter = self::getTwitterFromSettings($settings);

        return $twitter->getUser($settings['username']);
    }

    /**
     * Twitter constructor.
     * @throws \TYPO3\CMS\Core\Cache\Exception\NoSuchCacheException
     */
    public function __construct()
    {
        /** @var \TYPO3\CMS\Core\Cache\CacheManager $cacheManager */
        $cacheManager = GeneralUtility::makeInstance(CacheManager::class);
        $this->cache = $cacheManager->getCache('cwtwitter_queries');
    }

    /**
     * Sets consumer based on key and secret
     *
     * @param string $key
     * @param string $secret
     * @return void
     */
    public function setConsumer($key, $secret)
    {
        $this->oauthParameters['consumer_key'] = $key;
        $this->oauthParameters['consumer_secret'] = $secret;
    }

    /**
     * Sets token based on key and secret
     *
     * @param string $token
     * @param string $secret
     * @return void
     */
    public function setToken($token, $secret)
    {
        $this->oauthParameters['token'] = $token;
        $this->oauthParameters['token_secret'] = $secret;
    }

    /**
     * Get tweets from timeline from a specific user
     *
     * @param string $user
     * @param int $limit
     * @param bool $exclude_replies
     * @param bool $enhanced_privacy
     * @param bool $extended_tweet_mode
     * @param bool $include_rts
     * @return array
     * @throws ConfigurationException
     * @throws RequestException
     */
    public function getTweetsFromTimeline(
        $user = null,
        $limit = null,
        $exclude_replies = false,
        $enhanced_privacy = false,
        $extended_tweet_mode = true,
        $include_rts = true
    ) {
        $params = [
            'exclude_replies' => $exclude_replies ? 'true' : 'false',
            'include_rts' => $include_rts ? 'true' : 'false',
        ];

        if ($extended_tweet_mode) {
            $params['tweet_mode'] = 'extended';
        }

        if ($user) {
            $params['screen_name'] = $user;
        }

        if ($limit) {
            $params['count'] = $limit;
        }

        $tweets = $this->getData('statuses/user_timeline', $params);
        if ($enhanced_privacy) {
            $this->saveTweetPicturesLocally($tweets);
        }

        return $tweets;
    }

    /**
     * Search for tweets with specific query
     *
     * @param string $query
     * @param int $limit
     * @param bool $enhanced_privacy
     * @return array
     * @throws ConfigurationException
     * @throws RequestException
     */
    public function getTweetsFromSearch($query, $limit = null, $enhanced_privacy = false)
    {
        $params = [
            'q' => $query,
        ];

        if ($limit) {
            $params['count'] = $limit;
        }

        $data = $this->getData('search/tweets', $params);
        $tweets = $data['statuses'];

        if ($enhanced_privacy) {
            $this->saveTweetPicturesLocally($tweets);
        }

        return $tweets;
    }

    /**
     * Returns the user object for specified user
     *
     * @param string $user
     * @return array
     * @throws ConfigurationException
     * @throws RequestException
     */
    public function getUser($user)
    {
        return $this->getData(
            'users/show',
            ['screen_name' => $user]
        );
    }

    /**
     *
     * @param string $path
     * @param array $params
     * @param string $method
     * @return array
     * @throws RequestException
     */
    protected function getData($path, $params, $method = 'GET')
    {
        if ($method === 'GET' && $this->cache->has($this->calculateCacheKey($path, $params))) {
            return $this->cache->get($this->calculateCacheKey($path, $params));
        }

        /** @var \CW\CwTwitter\Http\RequestFactory $requestFactory */
        $requestFactory = GeneralUtility::makeInstance(RequestFactory::class);

        try {
            $response = $requestFactory->oauthRequest(
                $this->api_url . $path . '.json?' . http_build_query($params),
                $method,
                ['timeout' => 5.0],
                $this->oauthParameters
            );
        } catch (GuzzleException $e) {
            throw new RequestException(sprintf("Error in request: '%s'", $e->getMessage()), 1362059229);
        }

        $response = json_decode($response->getBody()->getContents(), true);

        if (isset($response['errors'])) {
            $msg = 'Error(s) in Request:';
            foreach ($response['errors'] as $error) {
                $msg .= sprintf("\n%d: %s", $error['code'], $error['message']);
            }
            throw new RequestException($msg, 1362059237);
        }

        if ($method === 'GET') {
            if (VersionNumberUtility::convertVersionNumberToInteger(TYPO3_version) >= 9000000) {
                $cacheLifetime = $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['cw_twitter']['lifetime'];
            } else {
                $extConf = unserialize(
                    $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['cw_twitter'] ?? '',
                    ['allowed_classes' => false]
                );
                $cacheLifetime = $extConf['lifetime'] ?: null;
            }

            $this->cache->set($this->calculateCacheKey($path, $params), $response, [], $cacheLifetime);
        }

        return $response;
    }

    /**
     * Calculates the cache key
     *
     * @param string $path
     * @param array $params
     * @return string
     */
    protected function calculateCacheKey($path, $params)
    {
        return md5(sprintf('%s|%s', $path, implode(',', $params)));
    }

    /**
     * Saves profile pictures locally
     *
     * @param array $tweets
     * @return void
     */
    protected function saveTweetPicturesLocally(array &$tweets)
    {
        foreach ($tweets as &$tweet) {
            if (!empty($tweet['user']['profile_image_url'])) {
                $tweet['user']['profile_image_url'] = $this->saveUserPic($tweet['user']['profile_image_url']);
            }

            if (!empty($tweet['retweeted_status']['user']['profile_image_url'])) {
                $tweet['retweeted_status']['user']['profile_image_url'] =
                    $this->saveUserPic($tweet['retweeted_status']['user']['profile_image_url']);
            }
        }
    }

    /**
     * Saves the profile picture to typo3temp/cw_twitter/
     *
     * @param string $url URL of the picture
     * @return string path to the new picture on the server
     */
    protected function saveUserPic($url)
    {
        // directory to store the images
        $tempPath = 'typo3temp/cw_twitter/';

        if (!file_exists($tempPath)) {
            mkdir($tempPath);
        }

        // get the upstream filename
        $filename = basename($url);

        $tempFile = $tempPath . $filename;

        if (!file_exists($tempFile)) {
            $contents = file_get_contents($url);
            file_put_contents($tempFile, $contents);
        }
        return $tempFile;
    }
}
