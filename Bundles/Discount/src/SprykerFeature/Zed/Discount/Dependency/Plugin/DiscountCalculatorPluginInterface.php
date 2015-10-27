<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Dependency\Plugin;

use Generated\Shared\Discount\DiscountInterface;
use SprykerFeature\Zed\Discount\Business\Model\DiscountableInterface;

interface DiscountCalculatorPluginInterface
{

    /**
     * @param DiscountableInterface[] $discountableObjects
     * @param float $number
     *
     * @return
     */
    public function calculate(array $discountableObjects, $number);

    /**
     * @param DiscountableInterface[] $discountableObjects
     * @param DiscountInterface $discountTransfer
     */
    public function distribute(array $discountableObjects, DiscountInterface $discountTransfer);

    /**
     * @return float
     */
    public function getMinValue();

    /**
     * @return float
     */
    public function getMaxValue();

    /**
     * @param DiscountInterface $discountTransfer
     *
     * @return string
     */
    public function getFormattedAmount(DiscountInterface $discountTransfer);

}
