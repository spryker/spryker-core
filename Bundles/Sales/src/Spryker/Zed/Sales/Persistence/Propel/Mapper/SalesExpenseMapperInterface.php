<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ExpenseTransfer;
use Orm\Zed\Sales\Persistence\SpySalesExpense;
use Propel\Runtime\Collection\Collection;

interface SalesExpenseMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesExpense $salesExpenseEntity
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesExpense
     */
    public function mapExpenseTransferToSalesExpenseEntity(ExpenseTransfer $expenseTransfer, SpySalesExpense $salesExpenseEntity): SpySalesExpense;

    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesExpense $expenseEntity
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    public function mapExpenseEntityToSalesExpenseTransfer(ExpenseTransfer $expenseTransfer, SpySalesExpense $expenseEntity): ExpenseTransfer;

    /**
     * @param \Propel\Runtime\Collection\Collection<\Orm\Zed\Sales\Persistence\SpySalesExpense> $salesExpenseEntities
     * @param list<\Generated\Shared\Transfer\ExpenseTransfer> $expenseTransfers
     *
     * @return list<\Generated\Shared\Transfer\ExpenseTransfer>
     */
    public function mapSalesExpenseEntitiesToExpenseTransfers(
        Collection $salesExpenseEntities,
        array $expenseTransfers
    ): array;
}
