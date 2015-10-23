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
     * @param mixed $number
     *
     * @return mixed
     */
    public function calculate(array $discountableObjects, $number);

    /**
     * @param DiscountInterface $discountTransfer
     *
     * @return string
     */
    public function getFormattedAmount(DiscountInterface $discountTransfer);

}
