<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Dependency\Facade;

use Generated\Shared\Transfer\MoneyTransfer;

class PriceProductScheduleGuiToMoneyFacadeBridge implements PriceProductScheduleGuiToMoneyFacadeInterface
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
     * @param \Generated\Shared\Transfer\MoneyTransfer $moneyTransfer
     *
     * @return string
     */
    public function formatWithoutSymbol(MoneyTransfer $moneyTransfer)
    {
        return $this->moneyFacade->formatWithoutSymbol($moneyTransfer);
    }

    /**
     * @param float $value
     *
     * @return int
     */
    public function convertDecimalToInteger($value)
    {
        return $this->moneyFacade->convertDecimalToInteger($value);
    }

    /**
     * @param int $value
     *
     * @return float
     */
    public function convertIntegerToDecimal($value)
    {
        return $this->moneyFacade->convertIntegerToDecimal($value);
    }
}
