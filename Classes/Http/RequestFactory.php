<?php
namespace CW\CwTwitter\Http;

/*
 *  (C) 2019 Luis Antonio García Escobar <louisantoniogarcia@gmail.com>
 *
 *  This file is part of the TYPO3 CMS project.
 *
 *  The TYPO3 CMS project is free software; you can redistribute it
 *  and/or modify it under the terms of the GNU General Public License
 *  as published by the Free Software Foundation; either version 3 of
 *  the License, or (at your option) any later version.
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
 */

use GuzzleHttp\HandlerStack;
use GuzzleHttp\Subscriber\Oauth\Oauth1;
use Psr\Http\Message\ResponseInterface;

/**
 * Class RequestFactory to create Request objects
 * Returns PSR-7 Request objects (currently the Guzzle implementation).
 */
class RequestFactory extends \TYPO3\CMS\Core\Http\RequestFactory
{
    /**
     * Create a request object with OAuth authentication.
     *
     * @param string $uri the URI to request
     * @param string $method the HTTP method (defaults to GET)
     * @param array $options custom options for this request
     * @param array $oauthOptions the OAuth parameters to be used in this request
     *
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function oauthRequest(
        string $uri,
        string $method = 'GET',
        array $options = [],
        array $oauthOptions = []
    ): ResponseInterface {
        if (empty($oauthOptions)) {
            return $this->request($uri, $method, $options);
        }

        $oauthMiddleware = new Oauth1($oauthOptions);

        if (isset($options['handler']) && $options['handler'] instanceof HandlerStack) {
            $options['handler']->push($oauthMiddleware);
        } else {
            $stack = HandlerStack::create();
            $stack->push($oauthMiddleware);
            $options['handler'] = $stack;
        }

        $options['auth'] = 'oauth';

        $client = $this->getClient();

        return $client->request($method, $uri, $options);
    }
}
