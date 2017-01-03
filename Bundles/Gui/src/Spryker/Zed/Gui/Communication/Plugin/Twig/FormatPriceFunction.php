<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Gui\Communication\Plugin\Twig;

use Spryker\Shared\Twig\TwigFunction;
use Spryker\Zed\Money\Communication\Plugin\MoneyPlugin;

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
            $moneyPlugin = new MoneyPlugin();
            $moneyTransfer = $moneyPlugin->fromInteger($value);

            if ($includeSymbol) {
                return $moneyPlugin->formatWithSymbol($moneyTransfer);
            }

            return $moneyPlugin->formatWithoutSymbol($moneyTransfer);
        };
    }

}
