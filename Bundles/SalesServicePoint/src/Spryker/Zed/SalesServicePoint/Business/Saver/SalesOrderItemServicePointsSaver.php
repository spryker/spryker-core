<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesServicePoint\Business\Saver;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SalesOrderItemServicePointCollectionTransfer;
use Generated\Shared\Transfer\SalesOrderItemServicePointTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\SalesServicePoint\Persistence\SalesServicePointEntityManagerInterface;

class SalesOrderItemServicePointsSaver implements SalesOrderItemServicePointsSaverInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\SalesServicePoint\Persistence\SalesServicePointEntityManagerInterface
     */
    protected SalesServicePointEntityManagerInterface $salesServicePointEntityManager;

    /**
     * @param \Spryker\Zed\SalesServicePoint\Persistence\SalesServicePointEntityManagerInterface $salesServicePointEntityManager
     */
    public function __construct(SalesServicePointEntityManagerInterface $salesServicePointEntityManager)
    {
        $this->salesServicePointEntityManager = $salesServicePointEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function saveSalesOrderItemServicePointsFromQuote(QuoteTransfer $quoteTransfer): void
    {
        $salesOrderItemServicePointCollectionTransfer = $this->createSalesOrderItemServicePointCollectionTransfer($quoteTransfer);

        if ($salesOrderItemServicePointCollectionTransfer->getSalesOrderItemServicePoints()->count()) {
            $salesOrderItemServicePointTransfers = $this->getTransactionHandler()
                ->handleTransaction(function () use ($salesOrderItemServicePointCollectionTransfer) {
                    return $this->executeCreateSalesOrderItemServicePointCollectionTransaction($salesOrderItemServicePointCollectionTransfer);
                });
        }
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderItemServicePointCollectionTransfer $salesOrderItemServicePointCollectionTransfer
     *
     * @return list<\Generated\Shared\Transfer\SalesOrderItemServicePointTransfer>
     */
    protected function executeCreateSalesOrderItemServicePointCollectionTransaction(
        SalesOrderItemServicePointCollectionTransfer $salesOrderItemServicePointCollectionTransfer
    ): array {
        $salesOrderItemServicePointTransfers = [];

        foreach ($salesOrderItemServicePointCollectionTransfer->getSalesOrderItemServicePoints() as $salesOrderItemServicePointTransfer) {
            $salesOrderItemServicePointTransfers[] = $this->salesServicePointEntityManager->createSalesOrderItemServicePoint($salesOrderItemServicePointTransfer);
        }

        return $salesOrderItemServicePointTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemServicePointCollectionTransfer
     */
    protected function createSalesOrderItemServicePointCollectionTransfer(
        QuoteTransfer $quoteTransfer
    ): SalesOrderItemServicePointCollectionTransfer {
        $salesOrderItemServicePointCollectionTransfer = new SalesOrderItemServicePointCollectionTransfer();

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if (!$itemTransfer->getServicePoint()) {
                continue;
            }

            $salesOrderItemServicePointTransfer = $this->createSalesOrderItemServicePointTransfer($itemTransfer);

            $salesOrderItemServicePointCollectionTransfer->addSalesOrderItemServicePoint($salesOrderItemServicePointTransfer);
        }

        return $salesOrderItemServicePointCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemServicePointTransfer
     */
    protected function createSalesOrderItemServicePointTransfer(ItemTransfer $itemTransfer): SalesOrderItemServicePointTransfer
    {
        $servicePointTransfer = $itemTransfer->getServicePointOrFail();

        return (new SalesOrderItemServicePointTransfer())
            ->setName($servicePointTransfer->getNameOrFail())
            ->setKey($servicePointTransfer->getKeyOrFail())
            ->setIdSalesOrderItem($itemTransfer->getIdSalesOrderItemOrFail());
    }
}
