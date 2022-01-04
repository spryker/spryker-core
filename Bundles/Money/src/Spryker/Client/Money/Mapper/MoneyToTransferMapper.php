<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Money\Mapper;

use Generated\Shared\Transfer\CurrencyTransfer;
use Money\Money;
use Spryker\Client\Money\Dependency\Client\MoneyToCurrencyClientInterface;
use Spryker\Shared\Money\Mapper\MoneyToTransferMapper as SharedMoneyToTransferMapper;

class MoneyToTransferMapper extends SharedMoneyToTransferMapper
{
    /**
     * @var \Spryker\Client\Money\Dependency\Client\MoneyToCurrencyClientInterface
     */
    protected $currencyClient;

    /**
     * @param \Spryker\Client\Money\Dependency\Client\MoneyToCurrencyClientInterface $currencyClient
     */
    public function __construct(MoneyToCurrencyClientInterface $currencyClient)
    {
        $this->currencyClient = $currencyClient;
    }

    /**
     * @param \Money\Money $money
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    protected function getCurrencyTransfer(Money $money): CurrencyTransfer
    {
        return $this->currencyClient->fromIsoCode($money->getCurrency()->getCode());
    }
}
