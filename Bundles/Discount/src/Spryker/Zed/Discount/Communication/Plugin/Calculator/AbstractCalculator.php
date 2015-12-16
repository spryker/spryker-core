<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Discount\Communication\Plugin\Calculator;

use Spryker\Zed\Discount\Business\Model\DiscountableInterface;
use Spryker\Zed\Discount\Communication\DiscountDependencyContainer;
use Spryker\Zed\Discount\Communication\Plugin\AbstractDiscountPlugin;
use Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface;

/**
 * @method DiscountDependencyContainer getCommunicationFactory()
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
