<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Gui\Communication\Plugin\Twig;

use SprykerFeature\Zed\Application\Business\Url\Url;
use SprykerFeature\Zed\Library\Sanitize\Html;
use SprykerFeature\Zed\Library\Twig\TwigFunction;

class UrlFunction extends TwigFunction
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
        return function ($url, array $query = [], array $options = []) {
            $url = Url::generate($url, $query, $options);
            $html = Html::escape($url);

            return $html;
        };
    }

}
