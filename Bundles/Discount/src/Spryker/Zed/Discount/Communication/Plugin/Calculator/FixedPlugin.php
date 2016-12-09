<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication\Plugin\Calculator;

use Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\Validator\Constraints\Regex;

/**
 * @method \Spryker\Zed\Discount\Business\DiscountFacade getFacade()
 * @method \Spryker\Zed\Discount\Communication\DiscountCommunicationFactory getFactory()
 */
class FixedPlugin extends AbstractPlugin implements DiscountCalculatorPluginInterface
{

    /**
     * @param \Generated\Shared\Transfer\DiscountableItemTransfer[] $discountableItems
     * @param int $percentage
     *
     * @return float
     */
    public function calculate(array $discountableItems, $percentage)
    {
        return $this->getFacade()->calculateFixed($discountableItems, $percentage);
    }

    /**
     * @return \Spryker\Zed\Discount\Dependency\Facade\DiscountToMoneyInterface
     */
    protected function getMoneyPlugin()
    {
        return $this->getFactory()->getMoneyFacade();
    }

    /**
     * @param float $value
     *
     * @return int
     */
    public function transformForPersistence($value)
    {
        return $this->getMoneyPlugin()->convertDecimalToInteger((float)$value);
    }

    /**
     * @param int $value
     *
     * @return string
     */
    public function transformFromPersistence($value)
    {
        $moneyTransfer = $this->getMoneyPlugin()->fromInteger((int)$value);

        return $this->getMoneyPlugin()->formatWithoutSymbol($moneyTransfer);
    }

    /**
     * @param int $amount
     *
     * @return string
     */
    public function getFormattedAmount($amount)
    {
        $moneyTransfer = $this->getMoneyPlugin()->fromInteger($amount);

        return $this->getMoneyPlugin()->formatWithSymbol($moneyTransfer);
    }

    /**
     * @return array
     */
    public function getAmountValidators()
    {
        return [
            new Regex([
                'pattern' => '/[0-9\.\,]+/',
            ]),
        ];
    }

}
