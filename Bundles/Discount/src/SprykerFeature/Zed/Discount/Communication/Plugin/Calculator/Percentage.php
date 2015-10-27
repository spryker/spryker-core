<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Communication\Plugin\Calculator;

use Generated\Shared\Discount\DiscountInterface;
use SprykerFeature\Zed\Discount\Business\Model\DiscountableInterface;
use SprykerFeature\Zed\Discount\Communication\DiscountDependencyContainer;

/**
 * @method DiscountDependencyContainer getDependencyContainer()
 */
class Percentage extends AbstractCalculator
{

    const MIN_VALUE = 0.1;
    const MAX_VALUE = 100;

    /**
     * @param DiscountableInterface[] $discountableObjects
     * @param float $number
     *
     * @return float
     */
    public function calculate(array $discountableObjects, $number)
    {
        return $this->getDependencyContainer()
            ->getDiscountFacade()
            ->calculatePercentage($discountableObjects, $number);
    }

    /**
     * @return float
     */
    public function getMinValue()
    {
        return self::MIN_VALUE;
    }

    /**
     * @return float
     */
    public function getMaxValue()
    {
        return self::MAX_VALUE;
    }

    /**
     * @param DiscountInterface $discountTransfer
     *
     * @return string
     */
    public function getFormattedAmount(DiscountInterface $discountTransfer)
    {
        return $discountTransfer->getAmount() . '%';
    }

}
