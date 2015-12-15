<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Gui\Communication\Plugin\Twig;

use Spryker\Zed\Application\Business\Url\Url;
use Spryker\Zed\Library\Twig\TwigFunction;

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
            $html = $url->buildEscaped();

            return $html;
        };
    }

}
