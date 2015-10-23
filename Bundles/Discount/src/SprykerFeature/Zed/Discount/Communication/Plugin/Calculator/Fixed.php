<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Communication\Plugin\Calculator;

use Generated\Shared\Discount\DiscountInterface;
use SprykerFeature\Shared\Library\Currency\CurrencyManager;
use SprykerFeature\Zed\Discount\Business\Model\DiscountableInterface;
use SprykerFeature\Zed\Discount\Communication\DiscountDependencyContainer;

/**
 * @method DiscountDependencyContainer getDependencyContainer()
 */
class Fixed extends AbstractCalculator
{
    /**
     * @param DiscountableInterface[] $discountableObjects
     * @param int $number
     *
     * @return float
     */
    public function calculate(array $discountableObjects, $number)
    {
        return $this->getDependencyContainer()->getDiscountFacade()->calculateFixed($discountableObjects, $number);
    }

    /**
     * @param int $value
     *
     * @return int
     */
    public function transformForPersistence($value)
    {
        return $this->getCurrencyManager()->convertDecimalToCent($value);
    }

    /**
     * @param int $value
     *
     * @return float
     */
    public function transformFromPersistence($value)
    {
        return $this->getCurrencyManager()->convertCentToDecimal($value);
    }

    /**
     * @param DiscountInterface $discountTransfer
     *
     * @return string
     */
    public function getFormattedAmount(DiscountInterface $discountTransfer)
    {
        $currencyManager = CurrencyManager::getInstance();
        $discountAmount = $currencyManager->convertCentToDecimal($discountTransfer->getAmount());

        return $currencyManager->format($discountAmount);
    }
}
