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

    const MIN_VALUE = 0.1;

    /**
     * @param DiscountableInterface[] $discountableObjects
     * @param float $number
     *
     * @return float
     */
    public function calculate(array $discountableObjects, $number)
    {
        return $this->getDependencyContainer()->getDiscountFacade()->calculateFixed($discountableObjects, $number);
    }

    /**
     * @return float
     */
    public function getMinValue()
    {
        return self::MIN_VALUE;
    }

    /**
     * @return float|null
     */
    public function getMaxValue()
    {
        return;
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
