<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Gui\Communication\Plugin\Twig;

use Spryker\Zed\Library\Twig\TwigFunction;

class AssetsPathFunction extends TwigFunction
{

    /**
     * @return string
     */
    protected function getFunctionName()
    {
        return 'assetsPath';
    }

    /**
     * @return callable
     */
    protected function getFunction()
    {
        return function ($path) {
            $path = ltrim($path, '/');

            return '/assets/' . $path;
        };
    }

}
