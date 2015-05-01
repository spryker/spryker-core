<?php

namespace SprykerFeature\Zed\Discount\Dependency\Plugin;

use SprykerFeature\Zed\Discount\Business\Model\DiscountableInterface;

/**
 * Interface DiscountDecisionRulePluginInterface
 * @package SprykerFeature\Zed\Discount\Dependency\Plugin
 */
interface DiscountCalculatorPluginInterface
{
    /**
     * @param DiscountableInterface[] $discountableObjects
     * @param float $number
     * @return
     */
    public function calculate(array $discountableObjects, $number);

    /**
     * @param DiscountableInterface[] $discountableObjects
     * @param float $number
     */
    public function distribute(array $discountableObjects, $number);

    /**
     * @return float
     */
    public function getMinValue();

    /**
     * @return float
     */
    public function getMaxValue();
}
