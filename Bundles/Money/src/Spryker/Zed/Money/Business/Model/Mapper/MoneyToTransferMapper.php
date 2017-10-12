<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Money\Business\Model\Mapper;

use Money\Money;
use Spryker\Shared\Money\Mapper\MoneyToTransferMapper as SharedMoneyToTransferMapper;
use Spryker\Zed\Money\Dependency\Facade\MoneyToCurrencyInterface;

class MoneyToTransferMapper extends SharedMoneyToTransferMapper
{
    /**
     * @var \Spryker\Zed\Money\Dependency\Facade\MoneyToCurrencyInterface
     */
    protected $currencyFacade;

    /**
     * @param \Spryker\Zed\Money\Dependency\Facade\MoneyToCurrencyInterface $currencyFacade
     */
    public function __construct(MoneyToCurrencyInterface $currencyFacade)
    {
        $this->currencyFacade = $currencyFacade;
    }

    /**
     * @param \Money\Money $money
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    protected function getCurrencyTransfer(Money $money)
    {
        return $this->currencyFacade->fromIsoCode($money->getCurrency()->getCode());
    }
}
