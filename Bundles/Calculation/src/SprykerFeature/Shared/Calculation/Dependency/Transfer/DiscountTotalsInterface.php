<?php

namespace SprykerFeature\Shared\Calculation\Dependency\Transfer;

use Generated\Shared\Transfer\SalesDiscountTotalItemTransfer;
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
     * @param \ArrayObject $discountItems
     *
     * @return $this
     */
    public function setDiscountItems(\ArrayObject $discountItems);

    /**
     * @return DiscountTotalItemInterface[]|\ArrayObject
     */
    public function getDiscountItems();

    /**
     * @param SalesDiscountTotalItemTransfer $discountItem
     *
     * @return $this
     */
    public function addDiscountItem(SalesDiscountTotalItemTransfer $discountItem);

}
