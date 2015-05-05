<?php

namespace SprykerFeature\Shared\Calculation\Dependency\Transfer;

use SprykerEngine\Shared\Transfer\TransferInterface;

interface DiscountTotalsInterface extends TransferInterface
{
    /**
     * @param int $totalAmount
     *
     * @return $this
     */
    public function setTotalAmount($totalAmount);

    /**
     * @return int
     */
    public function getTotalAmount();

    /**
     * @param DiscountTotalItemCollectionInterface $discountItems
     *
     * @return $this
     */
    public function setDiscountItems(DiscountTotalItemCollectionInterface $discountItems);

    /**
     * @return DiscountTotalItemInterface[]|DiscountTotalItemCollectionInterface
     */
    public function getDiscountItems();

    /**
     * @param DiscountTotalItemInterface $discountItem
     *
     * @return $this
     */
    public function addDiscountItem(DiscountTotalItemInterface $discountItem);

    /**
     * @param DiscountTotalItemInterface $discountItem
     *
     * @return $this
     */
    public function removeDiscountItem(DiscountTotalItemInterface $discountItem);
}
