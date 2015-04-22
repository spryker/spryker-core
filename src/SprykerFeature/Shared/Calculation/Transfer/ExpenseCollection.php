<?php

namespace SprykerFeature\Shared\Calculation\Transfer;

use SprykerFeature\Shared\Calculation\Dependency\Transfer\ExpenseItemInterface;
use SprykerFeature\Shared\Library\TransferObject\AbstractTransferCollection;
use SprykerFeature\Shared\Calculation\Dependency\Transfer\ExpenseItemCollectionInterface;

class ExpenseCollection extends AbstractTransferCollection implements ExpenseItemCollectionInterface
{
    /**
     * @var ExpenseItemInterface
     */
    protected $transferObjectClass = 'Calculation\\Expense';
}
