<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Dependency\Plugin;

use Generated\Shared\Transfer\DiscountTransfer;
use SprykerFeature\Zed\Discount\Business\Model\DiscountableInterface;

interface DiscountCalculatorPluginInterface
{

    /**
     * @param DiscountableInterface[] $discountableObjects
     * @param mixed $number
     *
     * @return int
     */
    public function calculate(array $discountableObjects, $number);

    /**
     * @param DiscountTransfer $discountTransfer
     *
     * @return string
     */
    public function getFormattedAmount(DiscountTransfer $discountTransfer);

}
