<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Discount\Dependency\Plugin;

use Generated\Shared\Transfer\DiscountTransfer;
use Spryker\Zed\Discount\Business\Model\DiscountableInterface;

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
