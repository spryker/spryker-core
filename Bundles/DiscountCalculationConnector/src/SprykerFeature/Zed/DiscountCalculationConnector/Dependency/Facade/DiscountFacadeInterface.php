<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\DiscountCalculationConnector\Dependency\Facade;

use Generated\Shared\DiscountCalculationConnector\OrderInterface;

interface DiscountFacadeInterface
{
    /**
     * @param OrderInterface $calculableContainer
     *
     * @return array
     */
    public function calculateDiscounts(OrderInterface $calculableContainer);
}
