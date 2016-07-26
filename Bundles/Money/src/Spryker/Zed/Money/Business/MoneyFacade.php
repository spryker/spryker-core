<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Money\Business;

use Generated\Shared\Transfer\MoneyTransfer;
use Spryker\Shared\Money\MoneyConstants;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Money\Business\MoneyBusinessFactory getFactory()
 */
class MoneyFacade extends AbstractFacade implements MoneyFacadeInterface
{

    /**
     * Specification:
     * - Converts int amount and currency to MoneyTransfer Object
     * - If currency is not provided it will use from Store configured one
     *
     * @api
     *
     * @param int $amount
     * @param string|null $currency
     *
     * @return \Generated\Shared\Transfer\MoneyTransfer
     */
    public function fromInteger($amount, $currency = null)
    {
        return $this->getFactory()->createMoneyBuilder()->fromInteger($amount, $currency);
    }

    /**
     * Specification:
     * - Converts float amount and currency to MoneyTransfer Object
     * - If currency is not provided it will use from Store configured one
     *
     * @api
     *
     * @param float $amount
     * @param string|null $currency
     *
     * @return \Generated\Shared\Transfer\MoneyTransfer
     */
    public function fromFloat($amount, $currency = null)
    {
        return $this->getFactory()->createMoneyBuilder()->fromFloat($amount, $currency);
    }

    /**
     * Specification:
     * - Converts string amount and currency to MoneyTransfer Object
     * - If currency is not provided it will use from Store configured one
     *
     * @api
     *
     * @param string $amount
     * @param string|null $currency
     *
     * @return \Generated\Shared\Transfer\MoneyTransfer
     */
    public function fromString($amount, $currency = null)
    {
        return $this->getFactory()->createMoneyBuilder()->fromString($amount, $currency);
    }

    /**
     * Specification:
     * - Converts MoneyTransfer Object into string representation with currency symbol
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MoneyTransfer $moneyTransfer
     *
     * @return string
     */
    public function formatWithSymbol(MoneyTransfer $moneyTransfer)
    {
        return $this->getFactory()->createMoneyFormatter()->format(
            $moneyTransfer,
            MoneyConstants::FORMATTER_WITH_SYMBOL
        );
    }

    /**
     * Specification:
     * - Converts MoneyTransfer Object into string representation without currency symbol
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MoneyTransfer $moneyTransfer
     *
     * @return string
     */
    public function formatWithoutSymbol(MoneyTransfer $moneyTransfer)
    {
        return $this->getFactory()->createMoneyFormatter()->format(
            $moneyTransfer,
            MoneyConstants::FORMATTER_WITHOUT_SYMBOL
        );
    }

    /**
     * Specification
     * - Converts a cent value into decimal value
     *
     * @api
     *
     * @param int $value
     *
     * @return float
     */
    public function convertIntegerToDecimal($value)
    {
        return $this->getFactory()->createIntegerToDecimalConverter()->convert($value);
    }

    /**
     * Specification
     * - Converts a decimal value into cent value
     *
     * @api
     *
     * @param float $value
     *
     * @return int
     */
    public function convertDecimalToInteger($value)
    {
        return $this->getFactory()->createDecimalToIntegerConverter()->convert($value);
    }

}
