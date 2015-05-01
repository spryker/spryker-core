<?php

namespace SprykerFeature\Zed\Discount\Dependency\Plugin;

use SprykerFeature\Zed\Discount\Business\Model\DiscountableInterface;
use SprykerFeature\Shared\Discount\Dependency\Transfer\DiscountableContainerInterface;

interface DiscountCollectorPluginInterface
{
    /**
     * @param DiscountableContainerInterface $container
     * @return DiscountableInterface[]
     */
    public function collect(DiscountableContainerInterface $container);
}
