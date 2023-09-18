<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxApp\Business\Mapper\Prices;

use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;

interface PriceFormatterInterface
{
    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer|\Generated\Shared\Transfer\ItemTransfer $transfer
     * @param string|null $priceMode
     *
     * @return string|null
     */
    public function getSumPrice(ExpenseTransfer|ItemTransfer $transfer, ?string $priceMode): ?string;

    /**
     * @param int|null $price
     *
     * @return string|null
     */
    public function priceToString(?int $price): ?string;
}
