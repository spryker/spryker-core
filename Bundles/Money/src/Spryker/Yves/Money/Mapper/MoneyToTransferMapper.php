<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Money\Mapper;

use Money\Money;
use Spryker\Shared\Money\Mapper\MoneyToTransferMapper as SharedMoneyToTransferMapper;
use Spryker\Yves\Currency\Plugin\Currency\CurrencyPlugin;

class MoneyToTransferMapper extends SharedMoneyToTransferMapper
{

    /**
     * @var \Spryker\Yves\Currency\Plugin\Currency\CurrencyPlugin
     */
    protected $currencyPlugin;

    /**
     * @param \Spryker\Yves\Currency\Plugin\Currency\CurrencyPlugin $currencyPlugin
     */
    public function __construct(CurrencyPlugin $currencyPlugin)
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
