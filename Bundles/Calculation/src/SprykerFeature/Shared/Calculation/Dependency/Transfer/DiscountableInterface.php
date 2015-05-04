<?php
namespace SprykerFeature\Shared\Calculation\Dependency\Transfer;

use Generated\Shared\Transfer\Discount\DependencyDiscountableItemCollectionInterfaceTransfer;
use Generated\Shared\Transfer\Discount\DependencyDiscountItemInterfaceTransfer;

interface DiscountableInterface
{
    /**
     * @param DiscountableItemCollectionInterface $discounts
     *
     * @return $this
     */
    public function setDiscounts(DiscountableItemCollectionInterface $discounts);

    /**
     * @return DiscountItemInterface[]|DiscountableItemCollectionInterface
     */
    public function getDiscounts();

    /**
     * @param DiscountItemInterface $discount
     *
     * @return $this
     */
    public function addDiscount(DiscountItemInterface $discount);

    /**
     * @param DiscountItemInterface $discount
     *
     * @return $this
     */
    public function removeDiscount(DiscountItemInterface $discount);
}
