<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesInvoice\Persistence;

use Generated\Shared\Transfer\OrderInvoiceCollectionTransfer;
use Generated\Shared\Transfer\OrderInvoiceCriteriaTransfer;
use Generated\Shared\Transfer\OrderInvoiceTransfer;
use Orm\Zed\SalesInvoice\Persistence\SpySalesOrderInvoiceQuery;
use Propel\Runtime\Formatter\ObjectFormatter;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\SalesInvoice\Persistence\SalesInvoicePersistenceFactory getFactory()
 */
class SalesInvoiceRepository extends AbstractRepository implements SalesInvoiceRepositoryInterface
{
    /**
     * @param int $orderId
     *
     * @return bool
     */
    public function checkOrderInvoiceExistenceByOrderId(int $orderId): bool
    {
        return $this->getFactory()
            ->getSalesOrderInvoicePropelQuery()
            ->filterByFkSalesOrder($orderId)
            ->exists();
    }

    /**
     * @param \Generated\Shared\Transfer\OrderInvoiceCriteriaTransfer $orderInvoiceCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\OrderInvoiceCollectionTransfer
     */
    public function getOrderInvoices(OrderInvoiceCriteriaTransfer $orderInvoiceCriteriaTransfer): OrderInvoiceCollectionTransfer
    {
        $salesOrderInvoiceQuery = $this->getFactory()
            ->getSalesOrderInvoicePropelQuery();

        $this->applyOrderInvoiceCriteria($orderInvoiceCriteriaTransfer, $salesOrderInvoiceQuery);

        $orderInvoiceEntities = $this->buildQueryFromCriteria($salesOrderInvoiceQuery, $orderInvoiceCriteriaTransfer->getFilter())
            ->setFormatter(ObjectFormatter::class)
            ->find();

        $orderInvoiceCollectionTransfer = new OrderInvoiceCollectionTransfer();
        foreach ($orderInvoiceEntities as $orderInvoiceEntity) {
            $orderInvoiceCollectionTransfer->addOrderInvoice(
                (new OrderInvoiceTransfer())
                    ->fromArray($orderInvoiceEntity->toArray(), true)
                    ->setIdSalesOrder($orderInvoiceEntity->getFkSalesOrder())
            );
        }

        return $orderInvoiceCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderInvoiceCriteriaTransfer $orderInvoiceCriteriaTransfer
     * @param \Orm\Zed\SalesInvoice\Persistence\SpySalesOrderInvoiceQuery $salesOrderInvoiceQuery
     *
     * @return void
     */
    protected function applyOrderInvoiceCriteria(
        OrderInvoiceCriteriaTransfer $orderInvoiceCriteriaTransfer,
        SpySalesOrderInvoiceQuery $salesOrderInvoiceQuery
    ): void {
        if ($orderInvoiceCriteriaTransfer->getSalesOrderIds()) {
            $salesOrderInvoiceQuery->filterByFkSalesOrder_In(
                $orderInvoiceCriteriaTransfer->getSalesOrderIds()
            );
        }

        if ($orderInvoiceCriteriaTransfer->getIsEmailSent() !== null) {
            $salesOrderInvoiceQuery->filterByEmailSent(
                $orderInvoiceCriteriaTransfer->getIsEmailSent()
            );
        }
    }
}
