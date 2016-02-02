<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Discount\Communication\Plugin\Calculator;

use Spryker\Zed\Discount\Communication\DiscountCommunicationFactory;
use Spryker\Zed\Discount\Communication\Plugin\AbstractDiscountPlugin;
use Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface;

/**
 * @method DiscountCommunicationFactory getFactory()
 */
abstract class AbstractCalculator extends AbstractDiscountPlugin implements DiscountCalculatorPluginInterface
{

    /**
     * @param \Spryker\Zed\Discount\Business\Model\DiscountableInterface[] $discountableObjects
     * @param float $number
     *
     * @return int
     */
    abstract public function calculate(array $discountableObjects, $number);

}
