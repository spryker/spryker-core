<?php

namespace SprykerFeature\Shared\Calculation\Transfer;

use Generated\Shared\Transfer\Calculation\DependencyExpenseItemInterfaceTransfer;
use SprykerFeature\Shared\Library\TransferObject\AbstractTransferCollection;
use Generated\Shared\Transfer\Calculation\DependencyExpenseItemCollectionInterfaceTransfer;

class ExpenseCollection extends AbstractTransferCollection implements ExpenseItemCollectionInterface
{
    /**
     * @var ExpenseItemInterface
     */
    protected $transferObjectClass = 'Calculation\\Expense';
}
