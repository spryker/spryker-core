<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Gui\Communication\Plugin\Twig;

use Spryker\Zed\Library\Twig\TwigFunction;

class StaticPath extends TwigFunction
{

    /**
     * @return string
     */
    protected function getFunctionName()
    {
        return 'staticPath';
    }

    /**
     * @return callable
     */
    protected function getFunction()
    {
        return function ($path) {
            ltrim($path, '/');

            return '/bundles/' . $path;
        };
    }

}
