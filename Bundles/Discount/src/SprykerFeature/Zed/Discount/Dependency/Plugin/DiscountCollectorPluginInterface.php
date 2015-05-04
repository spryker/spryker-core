<?php

namespace SprykerFeature\Zed\Discount\Dependency\Plugin;

use SprykerFeature\Zed\Discount\Business\Model\DiscountableInterface;
use Generated\Shared\Transfer\Discount\DependencyDiscountableContainerInterfaceTransfer;

interface DiscountCollectorPluginInterface
{
    /**
     * @param DiscountableContainerInterface $container
     * @return DiscountableInterface[]
     */
    public function collect(DiscountableContainerInterface $container);
}
