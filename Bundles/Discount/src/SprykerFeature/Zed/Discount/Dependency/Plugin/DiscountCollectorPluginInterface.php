<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Dependency\Plugin;

use Generated\Shared\Discount\OrderInterface;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;
use SprykerFeature\Zed\Discount\Business\Model\DiscountableInterface;

interface DiscountCollectorPluginInterface
{

    /**
     * @ param OrderInterface $container
     *
     * @param CalculableInterface $container
     *
     * @return DiscountableInterface[]
     */
    public function collect(CalculableInterface $container);
    //public function collect(OrderInterface $container);


}
