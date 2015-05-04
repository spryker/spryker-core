<?php

namespace SprykerFeature\Shared\Sales\Transfer;

use Generated\Shared\Transfer\Discount\DependencyDiscountableItemCollectionInterfaceTransfer;
use Generated\Shared\Transfer\Discount\DependencyDiscountableItemInterfaceTransfer;
use SprykerFeature\Shared\Library\TransferObject\AbstractTransferCollection;

class OrderItemCollection extends AbstractTransferCollection implements DiscountableItemCollectionInterface
{
    /**
     * @var DiscountableItemInterface
     */
    protected $transferObjectClass = 'Sales\\OrderItem';
}
