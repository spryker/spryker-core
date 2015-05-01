<?php

namespace SprykerFeature\Shared\Sales\Transfer;

use SprykerFeature\Shared\Discount\Dependency\Transfer\DiscountableItemCollectionInterface;
use SprykerFeature\Shared\Discount\Dependency\Transfer\DiscountableItemInterface;
use SprykerFeature\Shared\Library\TransferObject\AbstractTransferCollection;

class OrderItemCollection extends AbstractTransferCollection implements DiscountableItemCollectionInterface
{
    /**
     * @var DiscountableItemInterface
     */
    protected $transferObjectClass = 'Sales\\OrderItem';
}
