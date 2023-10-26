<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxApp\Business\Mapper\Prices;

use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;

class ItemExpenseItemExpensePriceRetriever implements ItemExpensePriceRetrieverInterface
{
    /**
     * @uses \Spryker\Shared\Calculation\CalculationPriceMode::PRICE_MODE_NET
     *
     * @var string
     */
    public const PRICE_MODE_NET = 'NET_MODE';

    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer|\Generated\Shared\Transfer\ItemTransfer $transfer
     * @param string $priceMode
     *
     * @return int
     */
    public function getUnitPrice(ExpenseTransfer|ItemTransfer $transfer, string $priceMode): int
    {
        if (!method_exists($transfer, 'getUnitNetPrice') && !method_exists($transfer, 'getUnitGrossPrice')) {
            return 0;
        }

        if ($priceMode === static::PRICE_MODE_NET) {
            return $transfer->getUnitNetPriceOrFail();
        }

        return $transfer->getUnitGrossPriceOrFail();
    }

    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer|\Generated\Shared\Transfer\ItemTransfer $transfer
     * @param string $priceMode
     *
     * @return int
     */
    public function getSumPrice(ExpenseTransfer|ItemTransfer $transfer, string $priceMode): int
    {
        if (!method_exists($transfer, 'getSumNetPrice') && !method_exists($transfer, 'getSumGrossPrice')) {
            return 0;
        }

        if ($priceMode === static::PRICE_MODE_NET) {
            return $transfer->getSumNetPriceOrFail();
        }

        return $transfer->getSumGrossPriceOrFail();
    }
}
