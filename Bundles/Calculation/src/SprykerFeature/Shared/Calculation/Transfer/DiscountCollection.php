<?php

namespace SprykerFeature\Shared\Calculation\Transfer;

use SprykerFeature\Shared\Library\TransferObject\AbstractTransferCollection;
use Generated\Shared\Transfer\Discount\DependencyDiscountableItemCollectionInterfaceTransfer;

class DiscountCollection extends AbstractTransferCollection implements DiscountableItemCollectionInterface
{
    /**
     * @var string
     */
    protected $transferObjectClass = 'Calculation\\Discount';
}
