<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Communication\Plugin\Calculator;

use Generated\Shared\Discount\DiscountInterface;
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
     * @param array $discountableObjects
     * @param DiscountInterface $discountTransfer
     */
    public function distribute(array $discountableObjects, DiscountInterface $discountTransfer)
    {
        $this->getDependencyContainer()
            ->getDiscountFacade()
            ->distributeAmount($discountableObjects, $discountTransfer);
    }

}
