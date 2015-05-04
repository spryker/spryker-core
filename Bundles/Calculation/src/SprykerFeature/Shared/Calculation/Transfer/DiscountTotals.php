<?php

namespace SprykerFeature\Shared\Calculation\Transfer;

use Generated\Shared\Transfer\Calculation\DependencyDiscountTotalItemCollectionInterfaceTransfer;
use Generated\Shared\Transfer\Calculation\DependencyDiscountTotalItemInterfaceTransfer;
use Generated\Shared\Transfer\Calculation\DependencyDiscountTotalsInterfaceTransfer;
use SprykerFeature\Shared\Library\TransferObject\AbstractTransfer;

class DiscountTotals extends AbstractTransfer implements DiscountTotalsInterface
{
    /**
     * @var int
     */
    protected $totalAmount = 0;

    /**
     * @var DiscountTotalItemCollectionInterface
     */
    protected $discountItems = 'Calculation\\DiscountTotalItemCollection';

    /**
     * @param int $totalAmount
     *
     * @return $this
     */
    public function setTotalAmount($totalAmount)
    {
        $this->totalAmount = $totalAmount;
        $this->addModifiedProperty('totalAmount');

        return $this;
    }

    /**
     * @return int
     */
    public function getTotalAmount()
    {
        return $this->totalAmount;
    }

    /**
     * @param DiscountTotalItemCollectionInterface $discountItems
     *
     * @return $this
     */
    public function setDiscountItems(DiscountTotalItemCollectionInterface $discountItems)
    {
        $this->discountItems = $discountItems;
        $this->addModifiedProperty('discountItems');

        return $this;
    }

    /**
     * @return DiscountTotalItemInterface[]|DiscountTotalItemCollectionInterface
     */
    public function getDiscountItems()
    {
        return $this->discountItems;
    }

    /**
     * @param DiscountTotalItemInterface $discountItem
     *
     * @return $this
     */
    public function addDiscountItem(DiscountTotalItemInterface $discountItem)
    {
        $this->discountItems->add($discountItem);
        $this->addModifiedProperty('discountItems');

        return $this;
    }

    /**
     * @param DiscountTotalItemInterface $discountItem
     *
     * @return $this
     */
    public function removeDiscountItem(DiscountTotalItemInterface $discountItem)
    {
        $this->discountItems->remove($discountItem);
        $this->addModifiedProperty('discountItems');

        return $this;
    }
}
