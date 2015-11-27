<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Gui\Communication\Plugin\Twig;

use SprykerFeature\Zed\Library\Sanitize\Html;
use SprykerFeature\Zed\Library\Twig\TwigFunction;

class Url extends TwigFunction
{

    /**
     * @return string
     */
    protected function getFunctionName()
    {
        return 'url';
    }

    /**
     * @return callable
     */
    protected function getFunction()
    {
        //TODO: CD-524 URL class for query strings and absolute URLs
        return function ($url, array $query = [], $full = false) {
            $html = Html::escape($url);

            return $html;
        };
    }

}
