<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Application\Business\Model\Twig;

use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Library\System;
use Spryker\Zed\Library\Twig\TwigFunction;

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
                . '<dd>' . Store::getInstance()->getCurrentLocale()
                . '<dt>Store:'
                . '<dd>' . Store::getInstance()->getStoreName()
                . '<dt>Server:'
                . '<dd>' . System::getHostName()
                . '<dt>Controller:'
                . '<dd>' . $currentController
                . '</dl></div>';

            return $html;
        };
    }

}
