<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Communication\Plugin\Calculator;

use SprykerFeature\Zed\Discount\Business\Model\DiscountableInterface;
use SprykerFeature\Zed\Discount\Communication\DiscountDependencyContainer;
use SprykerFeature\Zed\Discount\Communication\Plugin\AbstractDiscountPlugin;
use SprykerFeature\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface;

/**
 * @method DiscountDependencyContainer getDependencyContainer()
 */
abstract class AbstractCalculator extends AbstractDiscountPlugin implements DiscountCalculatorPluginInterface
{

    /**
     * @param DiscountableInterface[] $discountableObjects
     * @param float $number
     *
     * @return int
     */
    abstract public function calculate(array $discountableObjects, $number);
}
