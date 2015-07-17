<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Communication\Plugin\Calculator;

use SprykerFeature\Zed\Discount\Business\Model\DiscountableInterface;
use SprykerFeature\Zed\Discount\Communication\DiscountDependencyContainer;

/**
 * Class DecisionRuleMinimumCartSubtotal
 */
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
        return $this->getDependencyContainer()
            ->getDiscountFacade()
            ->calculateFixed($discountableObjects, $number);
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

}
