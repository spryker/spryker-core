<?php

namespace SprykerFeature\Zed\Discount\Business\Collector;

use Generated\Shared\Transfer\Discount\DependencyDiscountableContainerInterfaceTransfer;

interface CollectorInterface
{
    /**
     * @param DiscountableContainerInterface $container
     * @return DiscountableContainerInterface[]
     */
    public function collect(DiscountableContainerInterface $container);
}
