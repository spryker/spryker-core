<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Gui\Communication\Plugin\Twig;

use Spryker\Shared\Library\Currency\CurrencyManager;
use Spryker\Shared\Twig\TwigFunction;

class FormatPriceFunction extends TwigFunction
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
