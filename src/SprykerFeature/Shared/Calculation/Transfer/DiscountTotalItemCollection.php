<?php 

namespace SprykerFeature\Shared\Calculation\Transfer;

use SprykerFeature\Shared\Calculation\Dependency\Transfer\DiscountTotalItemCollectionInterface;
use SprykerFeature\Shared\Library\TransferObject\AbstractTransferCollection;

class DiscountTotalItemCollection extends AbstractTransferCollection implements DiscountTotalItemCollectionInterface
{
    /**
     * @var string
     */
    protected $transferObjectClass = 'Calculation\\DiscountTotalItem';
}
