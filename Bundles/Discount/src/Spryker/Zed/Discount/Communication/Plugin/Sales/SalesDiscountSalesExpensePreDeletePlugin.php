<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication\Plugin\Sales;

use Generated\Shared\Transfer\SalesDiscountCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\SalesExpenseCollectionTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesExtension\Dependency\Plugin\SalesExpensePreDeletePluginInterface;

/**
 * @method \Spryker\Zed\Discount\DiscountConfig getConfig()
 * @method \Spryker\Zed\Discount\Business\DiscountFacadeInterface getFacade()
 * @method \Spryker\Zed\Discount\Communication\DiscountCommunicationFactory getFactory()
 */
class SalesDiscountSalesExpensePreDeletePlugin extends AbstractPlugin implements SalesExpensePreDeletePluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `ExpenseTransfer.idSalesExpense` to be set for each expense in the array.
     * - Does nothing if no expense IDs are provided.
     * - Deletes sales discount entities related to provided expenses.
     * - Deletes sales discount code entities related to provided expenses.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SalesExpenseCollectionTransfer $salesExpenseCollectionTransfer
     *
     * @return void
     */
    public function preDelete(SalesExpenseCollectionTransfer $salesExpenseCollectionTransfer): void
    {
        $salesExpenseIds = [];
        foreach ($salesExpenseCollectionTransfer->getExpenses() as $expenseTransfer) {
            $salesExpenseIds[] = $expenseTransfer->getIdSalesExpenseOrFail();
        }

        $this->getFacade()->deleteSalesDiscounts(
            (new SalesDiscountCollectionDeleteCriteriaTransfer())->setSalesExpenseIds($salesExpenseIds),
        );
    }
}
