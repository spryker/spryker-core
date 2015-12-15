<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Gui\Communication\Plugin\Twig;

use Spryker\Shared\Library\Currency\CurrencyManager;
use Spryker\Zed\Library\Twig\TwigFunction;

class FormatPrice extends TwigFunction
{

    /**
     * @return string
     */
    protected function getFunctionName()
    {
        return 'formatPrice';
    }

    /**
     * @return callable
     */
    protected function getFunction()
    {
        return function ($value, $includeSymbol = true) {
            $currencyManager = CurrencyManager::getInstance();
            $value = $currencyManager->convertCentToDecimal($value);

            return $currencyManager->format($value, $includeSymbol);
        };
    }

}
