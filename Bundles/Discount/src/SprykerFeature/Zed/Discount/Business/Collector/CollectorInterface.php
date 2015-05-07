<?php

namespace SprykerFeature\Zed\Discount\Business\Collector;

use SprykerFeature\Shared\Discount\Dependency\Transfer\DiscountableContainerInterface;

interface CollectorInterface
{
    /**
     * @param DiscountableContainerInterface $container
     *
     * @return DiscountableContainerInterface[]
     */
    public function collect(DiscountableContainerInterface $container);
}
