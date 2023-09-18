<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxApp\Business\Mapper\Prices;

use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;

class PriceFormatter implements PriceFormatterInterface
{
    /**
     * @uses \Spryker\Shared\Calculation\CalculationPriceMode::PRICE_MODE_NET
     *
     * @var string
     */
    public const PRICE_MODE_NET = 'NET_MODE';

    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer|\Generated\Shared\Transfer\ItemTransfer $transfer
     * @param string|null $priceMode
     *
     * @return string|null
     */
    public function getSumPrice(ExpenseTransfer|ItemTransfer $transfer, ?string $priceMode): ?string
    {
        if (!method_exists($transfer, 'getSumNetPrice') && !method_exists($transfer, 'getSumGrossPrice')) {
            return static::priceToString(0);
        }

        if ($priceMode === static::PRICE_MODE_NET) {
            return static::priceToString($transfer->getSumNetPrice());
        }

        return static::priceToString($transfer->getSumGrossPrice());
    }

    /**
     * @param int|null $price
     *
     * @return string|null
     */
    public function priceToString(?int $price): ?string
    {
        if ($price === null) {
            return null;
        }

        return (string)$price;
    }
}
