<?php

namespace SprykerFeature\Zed\Discount\Dependency\Plugin;

use SprykerFeature\Shared\Discount\Dependency\Transfer\DiscountableContainerInterface;
use SprykerFeature\Zed\Discount\Business\Model\DiscountableInterface;

interface DiscountCollectorPluginInterface
{
    /**
     * @param DiscountableContainerInterface $container
     * @return DiscountableInterface[]
     */
    public function collect(DiscountableContainerInterface $container);
}
