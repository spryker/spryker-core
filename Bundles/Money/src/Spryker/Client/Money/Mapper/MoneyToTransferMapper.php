<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Money\Mapper;

use Money\Money;
use Spryker\Client\Currency\Plugin\CurrencyPluginInterface;
use Spryker\Shared\Money\Mapper\MoneyToTransferMapper as SharedMoneyToTransferMapper;

class MoneyToTransferMapper extends SharedMoneyToTransferMapper
{
    /**
     * @var \Spryker\Client\Currency\Plugin\CurrencyPluginInterface
     */
    protected $currencyPlugin;

    /**
     * @param \Spryker\Client\Currency\Plugin\CurrencyPluginInterface $currencyPlugin
     */
    public function __construct(CurrencyPluginInterface $currencyPlugin)
    {
        $this->currencyPlugin = $currencyPlugin;
    }

    /**
     * @param \Money\Money $money
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    protected function getCurrencyTransfer(Money $money)
    {
        return $this->currencyPlugin->fromIsoCode($money->getCurrency()->getCode());
    }
}
