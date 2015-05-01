<?php

namespace SprykerFeature\Shared\Discount\Dependency\Transfer;

interface DiscountableOptionsInterface
{

    /**
     * @return DiscountItemInterface[]
     */
    public function getDiscounts();

    /**
     * @param DiscountableItemCollectionInterface $collection
     */
    public function setDiscounts(DiscountableItemCollectionInterface $collection);
}
