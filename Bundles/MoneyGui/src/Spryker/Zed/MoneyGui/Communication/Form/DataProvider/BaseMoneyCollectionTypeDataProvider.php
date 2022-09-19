<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MoneyGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\MoneyValueCollectionTransfer;

abstract class BaseMoneyCollectionTypeDataProvider
{
    /**
     * @param \Generated\Shared\Transfer\MoneyValueCollectionTransfer $moneyValueCollectionTransfer
     *
     * @return array<string, bool>
     */
    protected function createCurrencyIndexMap(MoneyValueCollectionTransfer $moneyValueCollectionTransfer): array
    {
        $currencyIndex = [];
        foreach ($moneyValueCollectionTransfer->getMoneyValues() as $moneyValueTransfer) {
            $currencyIndex[$moneyValueTransfer->getFkCurrency() . $moneyValueTransfer->getFkStore()] = true;
        }

        return $currencyIndex;
    }
}
