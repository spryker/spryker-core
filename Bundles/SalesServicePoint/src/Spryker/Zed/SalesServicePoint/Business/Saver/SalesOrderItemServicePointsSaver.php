<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesServicePoint\Business\Saver;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer;
use Generated\Shared\Transfer\SalesOrderItemServicePointCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\SalesOrderItemServicePointCollectionTransfer;
use Generated\Shared\Transfer\SalesOrderItemServicePointTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\SalesServicePoint\Business\Deleter\SalesOrderItemServicePointDeleterInterface;
use Spryker\Zed\SalesServicePoint\Persistence\SalesServicePointEntityManagerInterface;

class SalesOrderItemServicePointsSaver implements SalesOrderItemServicePointsSaverInterface
{
    use TransactionTrait;

    /**
     * @param \Spryker\Zed\SalesServicePoint\Persistence\SalesServicePointEntityManagerInterface $salesServicePointEntityManager
     * @param \Spryker\Zed\SalesServicePoint\Business\Deleter\SalesOrderItemServicePointDeleterInterface $salesOrderItemServicePointDeleter
     */
    public function __construct(
        protected SalesServicePointEntityManagerInterface $salesServicePointEntityManager,
        protected SalesOrderItemServicePointDeleterInterface $salesOrderItemServicePointDeleter
    ) {
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
            $this->getTransactionHandler()->handleTransaction(function () use ($salesOrderItemServicePointCollectionTransfer) {
                return $this->executeCreateSalesOrderItemServicePointCollectionTransaction($salesOrderItemServicePointCollectionTransfer);
            });
        }
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer $salesOrderItemCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer
     */
    public function updateSalesOrderItemServicePoints(
        SalesOrderItemCollectionResponseTransfer $salesOrderItemCollectionResponseTransfer
    ): SalesOrderItemCollectionResponseTransfer {
        $itemTransfers = $salesOrderItemCollectionResponseTransfer->getItems();
        $salesOrderItemServicePointCollectionTransfer = $this->createSalesOrderItemServicePointCollectionTransfer(
            (new QuoteTransfer())->setItems(new ArrayObject($itemTransfers)),
        );
        $salesOrderItemIdsToDelete = $this->getSalesOrderItemIdsToDelete(new ArrayObject($itemTransfers));

        if (!$salesOrderItemServicePointCollectionTransfer->getSalesOrderItemServicePoints()->count() && !$salesOrderItemIdsToDelete) {
            return $salesOrderItemCollectionResponseTransfer;
        }

        $this->getTransactionHandler()->handleTransaction(function () use ($salesOrderItemServicePointCollectionTransfer, $salesOrderItemIdsToDelete): void {
            if ($salesOrderItemServicePointCollectionTransfer->getSalesOrderItemServicePoints()->count()) {
                $this->executeUpdateSalesOrderItemServicePointsTransaction($salesOrderItemServicePointCollectionTransfer);
            }

            if ($salesOrderItemIdsToDelete) {
                $this->salesOrderItemServicePointDeleter->deleteSalesOrderItemServicePointCollection(
                    (new SalesOrderItemServicePointCollectionDeleteCriteriaTransfer())->setSalesOrderItemIds($salesOrderItemIdsToDelete),
                );
            }
        });

        return $salesOrderItemCollectionResponseTransfer;
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
     * @param \Generated\Shared\Transfer\SalesOrderItemServicePointCollectionTransfer $salesOrderItemServicePointCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemServicePointCollectionTransfer
     */
    protected function executeUpdateSalesOrderItemServicePointsTransaction(
        SalesOrderItemServicePointCollectionTransfer $salesOrderItemServicePointCollectionTransfer
    ): SalesOrderItemServicePointCollectionTransfer {
        $salesOrderItemServicePointTransfers = [];
        foreach ($salesOrderItemServicePointCollectionTransfer->getSalesOrderItemServicePoints() as $salesOrderItemServicePointTransfer) {
            $salesOrderItemServicePointTransfers[] = $this->salesServicePointEntityManager
                ->saveSalesOrderItemServicePointByFkSalesOrderItem($salesOrderItemServicePointTransfer);
        }

        return $salesOrderItemServicePointCollectionTransfer->setSalesOrderItemServicePoints(
            new ArrayObject($salesOrderItemServicePointTransfers),
        );
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

    /**
     * @param \ArrayObject<int,\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return list<int>
     */
    protected function getSalesOrderItemIdsToDelete(ArrayObject $itemTransfers): array
    {
        $salesOrderItemIdsToDelete = [];
        foreach ($itemTransfers as $itemTransfer) {
            if (!$itemTransfer->getServicePoint()) {
                $salesOrderItemIdsToDelete[] = $itemTransfer->getIdSalesOrderItemOrFail();
            }
        }

        return $salesOrderItemIdsToDelete;
    }
}
