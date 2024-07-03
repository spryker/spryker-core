<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantCommissionConnector\Dependency\Facade;

class PriceProductMerchantCommissionConnectorToMoneyFacadeBridge implements PriceProductMerchantCommissionConnectorToMoneyFacadeInterface
{
    /**
     * @var \Spryker\Zed\Money\Business\MoneyFacadeInterface
     */
    protected $moneyFacade;

    /**
     * @param \Spryker\Zed\Money\Business\MoneyFacadeInterface $moneyFacade
     */
    public function __construct($moneyFacade)
    {
        $this->moneyFacade = $moneyFacade;
    }

    /**
     * @param float $value
     *
     * @return int
     */
    public function convertDecimalToInteger(float $value): int
    {
        return $this->moneyFacade->convertDecimalToInteger($value);
    }
}
