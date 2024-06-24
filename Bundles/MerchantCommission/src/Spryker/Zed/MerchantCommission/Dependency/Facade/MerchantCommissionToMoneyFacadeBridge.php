<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Dependency\Facade;

use Generated\Shared\Transfer\MoneyTransfer;

class MerchantCommissionToMoneyFacadeBridge implements MerchantCommissionToMoneyFacadeInterface
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
     * @param int $amount
     * @param string|null $isoCode
     *
     * @return \Generated\Shared\Transfer\MoneyTransfer
     */
    public function fromInteger(int $amount, ?string $isoCode = null): MoneyTransfer
    {
        return $this->moneyFacade->fromInteger($amount, $isoCode);
    }

    /**
     * @param \Generated\Shared\Transfer\MoneyTransfer $moneyTransfer
     *
     * @return string
     */
    public function formatWithSymbol(MoneyTransfer $moneyTransfer): string
    {
        return $this->moneyFacade->formatWithSymbol($moneyTransfer);
    }

    /**
     * @param int $value
     *
     * @return float
     */
    public function convertIntegerToDecimal(int $value): float
    {
        return $this->moneyFacade->convertIntegerToDecimal($value);
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
