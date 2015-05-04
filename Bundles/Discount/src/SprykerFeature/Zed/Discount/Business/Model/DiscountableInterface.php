<?php

namespace SprykerFeature\Zed\Discount\Business\Model;

use Generated\Shared\Transfer\Discount\DependencyDiscountableItemCollectionInterfaceTransfer;

interface DiscountableInterface
{
    /**
     * @return float
     */
    public function getGrossPrice();

    /**
     * @return DiscountableItemCollectionInterface
     */
    public function getDiscounts();

    /**
     * @param DiscountableItemCollectionInterface $discountCollection
     *
     * @return $this
     */
    public function setDiscounts(DiscountableItemCollectionInterface $discountCollection);
}
