<?php 

namespace SprykerFeature\Shared\Calculation\Transfer;

use SprykerFeature\Shared\Calculation\Dependency\Transfer\ExpenseTotalItemCollectionInterface;
use SprykerFeature\Shared\Calculation\Dependency\Transfer\ExpenseTotalItemInterface;
use SprykerFeature\Shared\Library\TransferObject\AbstractTransferCollection;

class ExpenseTotalItemCollection extends AbstractTransferCollection implements ExpenseTotalItemCollectionInterface
{
    /**
     * @var ExpenseTotalItemInterface
     */
    protected $transferObjectClass = 'Calculation\\ExpenseTotalItem';
}
