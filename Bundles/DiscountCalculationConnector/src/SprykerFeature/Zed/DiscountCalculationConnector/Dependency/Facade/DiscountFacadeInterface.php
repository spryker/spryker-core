<?php

namespace SprykerFeature\Zed\DiscountCalculationConnector\Dependency\Facade;

use SprykerFeature\Shared\Discount\Dependency\Transfer\DiscountableContainerInterface;

interface DiscountFacadeInterface
{
    /**
     * @param DiscountableContainerInterface $calculableContainer
     * @return array['discounts' => [], 'errors' => []]
     */
    public function calculateDiscounts(DiscountableContainerInterface $calculableContainer);
}
