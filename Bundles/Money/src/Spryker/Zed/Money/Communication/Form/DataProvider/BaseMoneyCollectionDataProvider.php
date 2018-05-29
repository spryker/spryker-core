<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Money\Communication\Form\DataProvider;

use ArrayObject;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\StoreTransfer;

class BaseMoneyCollectionDataProvider
{
    /**
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer|null $storeTransfer
     *
     * @return \Generated\Shared\Transfer\MoneyValueTransfer
     */
    protected function mapMoneyTransfer(CurrencyTransfer $currencyTransfer, ?StoreTransfer $storeTransfer = null)
    {
        $moneyValueTransfer = new MoneyValueTransfer();
        $moneyValueTransfer->setCurrency($currencyTransfer);
        $moneyValueTransfer->setFkCurrency($currencyTransfer->getIdCurrency());

        if ($storeTransfer) {
            $moneyValueTransfer->setFkStore($storeTransfer->getIdStore());
        }

        return $moneyValueTransfer;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\MoneyValueTransfer[] $submittedMoneyValueCollection
     *
     * @return array
     */
    protected function createCurrencyIndexMap(ArrayObject $submittedMoneyValueCollection)
    {
        $currencyIndex = [];
        foreach ($submittedMoneyValueCollection as $moneyValueTransfer) {
            $currencyIndex[$moneyValueTransfer->getFkCurrency() . $moneyValueTransfer->getFkStore()] = true;
        }
        return $currencyIndex;
    }
}
