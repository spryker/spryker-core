<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Price\Communication\Plugin\Twig\Filters;

use Spryker\Shared\Library\Currency\CurrencyManager;

class PriceFilter
{

    const DECIMALS = 2;

    const DIVIDER = 100;

    /**
     * @var \Spryker\Shared\Library\Currency\CurrencyManager
     */
    protected $currencyManager;

    /**
     * @param \Spryker\Shared\Library\Currency\CurrencyManager $currencyManager
     */
    public function __construct(CurrencyManager $currencyManager)
    {
        $this->currencyManager = $currencyManager;
    }

    /**
     * @param int $price
     *
     * @return string
     */
    public function getConvertedPrice($price)
    {
        return $this->currencyManager->format(
            $this->currencyManager->convertCentToDecimal($price)
        );
    }

}
