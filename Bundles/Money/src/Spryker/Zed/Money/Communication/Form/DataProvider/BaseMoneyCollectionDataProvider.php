<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Money\Communication\Form\DataProvider;

use ArrayObject;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;

class BaseMoneyCollectionDataProvider
{

    /**
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return \Generated\Shared\Transfer\MoneyValueTransfer
     */
    protected function mapMoneyTransfer(CurrencyTransfer $currencyTransfer)
    {
        $moneyValueTransfer = new MoneyValueTransfer();
        $moneyValueTransfer->setCurrency($currencyTransfer);
        $moneyValueTransfer->setFkCurrency($currencyTransfer->getIdCurrency());
        $moneyValueTransfer->setFkStore($currencyTransfer->getStore()->getIdStore());

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
            $idStore = $moneyValueTransfer->getCurrency()->getStore()->getIdStore();
            $currencyIndex[$moneyValueTransfer->getFkCurrency() . $idStore] = true;
        }
        return $currencyIndex;
    }

}
