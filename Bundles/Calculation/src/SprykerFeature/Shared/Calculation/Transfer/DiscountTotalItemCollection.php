<?php

namespace SprykerFeature\Shared\Calculation\Transfer;

use Generated\Shared\Transfer\Calculation\DependencyDiscountTotalItemCollectionInterfaceTransfer;
use SprykerFeature\Shared\Library\TransferObject\AbstractTransferCollection;

class DiscountTotalItemCollection extends AbstractTransferCollection implements DiscountTotalItemCollectionInterface
{
    /**
     * @var string
     */
    protected $transferObjectClass = 'Calculation\\DiscountTotalItem';
}
