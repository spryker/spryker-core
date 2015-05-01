<?php 

namespace SprykerFeature\Shared\Calculation\Transfer;

use SprykerFeature\Shared\Library\TransferObject\AbstractTransferCollection;
use SprykerFeature\Shared\Discount\Dependency\Transfer\DiscountableItemCollectionInterface;

class DiscountCollection extends AbstractTransferCollection implements DiscountableItemCollectionInterface
{
    /**
     * @var string
     */
    protected $transferObjectClass = 'Calculation\\Discount';
}
