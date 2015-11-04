<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Dependency\Plugin;

use Generated\Shared\Discount\DiscountCollectorInterface;
use Generated\Shared\Discount\DiscountInterface;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;
use SprykerFeature\Zed\Discount\Business\Model\DiscountableInterface;

interface DiscountCollectorPluginInterface
{

    /**
     * @param DiscountInterface $discount
     * @param CalculableInterface $container
     * @param DiscountCollectorInterface $discountCollectorTransfer
     *
     * @return DiscountableInterface[]
     */
    public function collect(
        DiscountInterface $discount,
        CalculableInterface $container,
        DiscountCollectorInterface $discountCollectorTransfer
    );

}
