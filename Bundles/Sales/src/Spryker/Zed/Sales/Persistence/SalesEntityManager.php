<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Persistence;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Generated\Shared\Transfer\SpySalesOrderAddressEntityTransfer;
use Generated\Shared\Transfer\SpySalesOrderEntityTransfer;
use Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer;
use Orm\Zed\Sales\Persistence\SpySalesExpense;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddress;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
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

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function saveSalesOrderTotals(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer): void
    {
        $salesOrderTotalsEntity = $this->getFactory()
            ->createSalesOrderTotalsMapper()
            ->mapSalesOrderTotalsEntity($quoteTransfer, $saveOrderTransfer);
        $salesOrderTotalsEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\SpySalesOrderEntityTransfer $salesOrderEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpySalesOrderEntityTransfer
     */
    public function saveOrderEntity(SpySalesOrderEntityTransfer $salesOrderEntityTransfer): SpySalesOrderEntityTransfer
    {
        $salesOrderEntity = $this->getFactory()
            ->createSalesOrderMapper()
            ->mapSalesOrderEntityTransferToSalesOrderEntity($salesOrderEntityTransfer, new SpySalesOrder());
        $salesOrderEntity->save();

        return $this->getFactory()
            ->createSalesOrderMapper()
            ->mapSalesOrderEntityToSalesOrderEntityTransfer($salesOrderEntityTransfer, $salesOrderEntity);
    }

    /**
     * @param \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer $salesOrderItemEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer
     */
    public function saveSalesOrderItems(SpySalesOrderItemEntityTransfer $salesOrderItemEntityTransfer): SpySalesOrderItemEntityTransfer
    {
        $salesOrderItemEntity = $this->getFactory()
            ->createSalesOrderItemMapper()
            ->mapSalesOrderItemEntityTransferToSalesOrderItemEntity($salesOrderItemEntityTransfer, new SpySalesOrderItem());
        $salesOrderItemEntity->save();

        return $this->getFactory()
            ->createSalesOrderItemMapper()
            ->mapSalesOrderItemEntityToSalesOrderItemEntityTransfer($salesOrderItemEntityTransfer, $salesOrderItemEntity);
    }

    /**
     * @param \Generated\Shared\Transfer\SpySalesOrderAddressEntityTransfer $salesOrderAddressEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpySalesOrderAddressEntityTransfer
     */
    public function saveSalesOrderAddressEntity(SpySalesOrderAddressEntityTransfer $salesOrderAddressEntityTransfer): SpySalesOrderAddressEntityTransfer
    {
        $salesOrderAddressEntity = $this->getFactory()
            ->createSalesOrderMapper()
            ->mapSalesOrderAddressEntityTransferToSalesOrderAddressEntity($salesOrderAddressEntityTransfer, new SpySalesOrderAddress());
        $salesOrderAddressEntity->save();

        return $this->getFactory()
            ->createSalesOrderMapper()
            ->mapSalesOrderAddressEntityToSalesOrderAddressEntityTransfer($salesOrderAddressEntityTransfer, $salesOrderAddressEntity);
    }
}
