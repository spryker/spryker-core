<?php

namespace SprykerFeature\Zed\DiscountCalculationConnector\Dependency\Facade;

use Generated\Shared\Transfer\Discount\DependencyDiscountableContainerInterfaceTransfer;

interface DiscountFacadeInterface
{
    /**
     * @param DiscountableContainerInterface $calculableContainer
     * @return array['discounts' => [], 'errors' => []]
     */
    public function calculateDiscounts(DiscountableContainerInterface $calculableContainer);
}
