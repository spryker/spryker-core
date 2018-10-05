<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThresholdGui\Dependency\Facade;

class SalesOrderThresholdGuiToMoneyFacadeBridge implements SalesOrderThresholdGuiToMoneyFacadeInterface
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
     * @param int $value
     *
     * @return float
     */
    public function convertIntegerToDecimal($value): float
    {
        return $this->moneyFacade->convertIntegerToDecimal($value);
    }

    /**
     * @param float $value
     *
     * @return int
     */
    public function convertDecimalToInteger($value): int
    {
        return $this->moneyFacade->convertDecimalToInteger($value);
    }
}
