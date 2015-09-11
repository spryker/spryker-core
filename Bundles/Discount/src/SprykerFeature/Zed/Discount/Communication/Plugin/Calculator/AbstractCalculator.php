<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Communication\Plugin\Calculator;

use SprykerFeature\Zed\Discount\Business\Model\DiscountableInterface;
use SprykerFeature\Zed\Discount\Communication\DiscountDependencyContainer;
use SprykerFeature\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method DiscountDependencyContainer getDependencyContainer()
 */
abstract class AbstractCalculator extends AbstractPlugin implements DiscountCalculatorPluginInterface
{

    /**
     * @param DiscountableInterface[] $discountableObjects
     * @param float $number
     *
     * @return
     */
    abstract public function calculate(array $discountableObjects, $number);

    /**
     * @param DiscountableInterface[] $discountableObjects
     * @param float $number
     */
    public function distribute(array $discountableObjects, $number)
    {
        $this->getDependencyContainer()
            ->getDiscountFacade()
            ->distributeAmount($discountableObjects, $number);
    }

}
