<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Application\Business\Model\Twig;

use SprykerFeature\Shared\Library\System;
use SprykerFeature\Zed\Library\Twig\TwigFunction;

class EnvironmentInfo extends TwigFunction
{

    /**
     * @return string
     */
    protected function getFunctionName()
    {
        return 'environmentInfo';
    }

    /**
     * @return callable
     */
    protected function getFunction()
    {
        return function ($currentController) {
            $html = '<div class="zed:header__environment"><i class="icon-cogs"></i>'
                . '<span>' . APPLICATION_ENV . '</span>'
                . '<dl>'
                . '<dt>Locale:'
                . '<dd>' . \SprykerEngine\Shared\Kernel\Store::getInstance()->getCurrentLocale()
                . '<dt>Store:'
                . '<dd>' . \SprykerEngine\Shared\Kernel\Store::getInstance()->getStoreName()
                . '<dt>Server:'
                . '<dd>' . System::getHostName()
                . '<dt>Controller:'
                . '<dd>' . $currentController
                . '</dl></div>';

            return $html;
        };
    }

}
