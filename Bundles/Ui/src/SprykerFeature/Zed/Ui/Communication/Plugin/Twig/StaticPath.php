<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Ui\Communication\Plugin\Twig;

use SprykerFeature\Zed\Library\Twig\TwigFunction;

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
