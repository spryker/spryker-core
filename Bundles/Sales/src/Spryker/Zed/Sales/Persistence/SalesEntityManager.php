<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Persistence;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Orm\Zed\Sales\Persistence\SpySalesExpense;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\Sales\Persistence\SalesPersistenceFactory getFactory()
 */
class SalesEntityManager extends AbstractEntityManager implements SalesEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    public function createSalesExpense(ExpenseTransfer $expenseTransfer): ExpenseTransfer
    {
        $salesOrderExpenseEntity = $this->getFactory()
            ->createSalesExpenseMapper()
            ->mapExpenseTransferToSalesExpenseEntity($expenseTransfer, new SpySalesExpense());

        $salesOrderExpenseEntity->save();

        $expenseTransfer->setIdSalesExpense($salesOrderExpenseEntity->getIdSalesExpense());

        return $expenseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    public function updateSalesExpense(ExpenseTransfer $expenseTransfer): ExpenseTransfer
    {
        $expenseTransfer->requireIdSalesExpense();

        $salesOrderExpenseEntity = $this->getFactory()
            ->createSalesExpenseQuery()
            ->findOneByIdSalesExpense($expenseTransfer->getIdSalesExpense());

        $salesOrderExpenseEntity = $this->getFactory()
            ->createSalesExpenseMapper()
            ->mapExpenseTransferToSalesExpenseEntity($expenseTransfer, $salesOrderExpenseEntity);

        $salesOrderExpenseEntity->save();

        return $expenseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    public function createSalesOrderAddress(AddressTransfer $addressTransfer): AddressTransfer
    {
        $salesOrderAddressEntity = $this->getFactory()
            ->createSalesOrderAddressMapper()
            ->mapAddressTransferToSalesOrderAddressEntity($addressTransfer);

        $salesOrderAddressEntity->save();

        $addressTransfer->setIdSalesOrderAddress($salesOrderAddressEntity->getIdSalesOrderAddress());

        return $addressTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    public function updateSalesOrderAddress(AddressTransfer $addressTransfer): AddressTransfer
    {
        $salesOrderAddressEntity = $this->getFactory()
            ->createSalesOrderAddressQuery()
            ->filterByIdSalesOrderAddress($addressTransfer->getIdSalesOrderAddress())
            ->findOne();

        $salesOrderAddressEntity->fromArray($addressTransfer->toArray());

        $salesOrderAddressEntity->save();

        $addressTransfer->setIdSalesOrderAddress($salesOrderAddressEntity->getIdSalesOrderAddress());

        return $addressTransfer;
    }
}
