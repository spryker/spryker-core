<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business\Model\Product;

use Generated\Shared\Transfer\MoneyValueTransfer;

class BaseProductPriceWriter
{
    /**
     * @param \Generated\Shared\Transfer\MoneyValueTransfer $moneyValueTransfer
     *
     * @return bool
     */
    protected function isEmptyMoneyValue(MoneyValueTransfer $moneyValueTransfer)
    {
        return (!$moneyValueTransfer->getIdEntity() && $moneyValueTransfer->getNetAmount() === null && $moneyValueTransfer->getGrossAmount() === null);
    }
}
