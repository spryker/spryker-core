<?php

namespace SprykerFeature\Shared\Calculation\Transfer;

use Generated\Shared\Transfer\Calculation\DependencyExpenseTotalItemCollectionInterfaceTransfer;
use Generated\Shared\Transfer\Calculation\DependencyExpenseTotalItemInterfaceTransfer;
use SprykerFeature\Shared\Library\TransferObject\AbstractTransferCollection;

class ExpenseTotalItemCollection extends AbstractTransferCollection implements ExpenseTotalItemCollectionInterface
{
    /**
     * @var ExpenseTotalItemInterface
     */
    protected $transferObjectClass = 'Calculation\\ExpenseTotalItem';
}
