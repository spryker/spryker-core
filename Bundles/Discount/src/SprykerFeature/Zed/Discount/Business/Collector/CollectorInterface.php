<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Business\Collector;

use Generated\Shared\Discount\OrderInterface;

interface CollectorInterface
{
    /**
     * @param OrderInterface $container
     *
     * @return OrderInterface[]
     */
    public function collect(OrderInterface $container);
}
