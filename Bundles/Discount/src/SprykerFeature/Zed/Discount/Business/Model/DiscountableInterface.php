<?php

namespace SprykerFeature\Zed\Discount\Business\Model;

use SprykerFeature\Shared\Discount\Dependency\Transfer\DiscountableItemCollectionInterface;

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
