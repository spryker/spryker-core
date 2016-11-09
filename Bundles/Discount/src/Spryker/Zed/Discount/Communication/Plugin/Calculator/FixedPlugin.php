<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication\Plugin\Calculator;

use Spryker\Shared\Library\Currency\CurrencyManager;
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
     * @return \Spryker\Shared\Library\Currency\CurrencyManager
     */
    protected function getCurrencyManager()
    {
        return CurrencyManager::getInstance();
    }

    /**
     * @param float $value
     *
     * @return int
     */
    public function transformForPersistence($value)
    {
        return (int)round($this->getCurrencyManager()->convertDecimalToCent($value));
    }

    /**
     * @param int $value
     *
     * @return string
     */
    public function transformFromPersistence($value)
    {
        return $this->getCurrencyManager()->format(
            $this->getCurrencyManager()->convertCentToDecimal($value),
            false
        );
    }

    /**
     * @return string
     */
    public function getFormattedAmount($amount)
    {
        $discountAmount = $this->getCurrencyManager()->convertCentToDecimal($amount);

        return $this->getCurrencyManager()->format($discountAmount);
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
