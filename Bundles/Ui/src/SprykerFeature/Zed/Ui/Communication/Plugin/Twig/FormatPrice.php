<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Ui\Communication\Plugin\Twig;

use SprykerFeature\Shared\Library\Currency\CurrencyManager;
use SprykerFeature\Zed\Library\Twig\TwigFunction;

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
