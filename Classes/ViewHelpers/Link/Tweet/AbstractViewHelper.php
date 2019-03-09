<?php
namespace CW\CwTwitter\ViewHelpers\Link\Tweet;

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

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper;

/**
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
abstract class AbstractViewHelper extends AbstractTagBasedViewHelper
{
    const BASE_URL = 'https://twitter.com/';

    const PATH = '';

    /**
     * @var string
     */
    protected $tagName = 'a';

    /**
     * Initialize arguments
     *
     * @return void
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerUniversalTagAttributes();
        $this->registerTagAttribute('name', 'string', 'Specifies the name of an anchor');
        $this->registerTagAttribute('rel', 'string', 'Specifies the relationship between the current document and the linked document');
        $this->registerTagAttribute('rev', 'string', 'Specifies the relationship between the linked document and the current document');
        $this->registerTagAttribute('target', 'string', 'Specifies where to open the linked document');
        $this->registerArgument('tweet', 'array', 'The tweet to be linked', true);
    }

    /**
     * Render a specific actionlink (favorite, reply, retweet, show) to a tweet
     *
     * @return string
     */
    public function render()
    {
        $this->tag->setContent($this->renderChildren());
        $path = str_replace(
            [
                '{id}',
                '{id_str}',
                '{user}'
            ],
            [
                $this->arguments['tweet']['id'],
                $this->arguments['tweet']['id_str'],
                $this->arguments['tweet']['user']['screen_name']
            ],
            static::PATH
        );
        $this->tag->addAttribute('href', static::BASE_URL . $path);

        return $this->tag->render();
    }
}
