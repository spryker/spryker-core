<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Money\Mapper;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MoneyTransfer;
use Money\Money;

class MoneyToTransferMapper implements MoneyToTransferMapperInterface
{

    /**
     * @param \Money\Money $money
     *
     * @return \Generated\Shared\Transfer\MoneyTransfer
     */
    public function convert(Money $money)
    {
        $moneyTransfer = new MoneyTransfer();
        $moneyTransfer->setAmount($money->getAmount());

        $currencyTransfer = new CurrencyTransfer();
        $currencyTransfer->setCode($money->getCurrency()->getCode());
        $moneyTransfer->setCurrency($currencyTransfer);

        return $moneyTransfer;
    }

}
