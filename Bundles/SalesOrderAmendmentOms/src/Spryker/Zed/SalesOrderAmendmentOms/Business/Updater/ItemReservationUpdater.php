<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendmentOms\Business\Updater;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ReservationRequestTransfer;
use Spryker\Zed\SalesOrderAmendmentOms\Business\Reader\SalesOrderAmendmentQuoteReaderInterface;
use Spryker\Zed\SalesOrderAmendmentOms\Dependency\Facade\SalesOrderAmendmentOmsToOmsFacadeInterface;
use Spryker\Zed\SalesOrderAmendmentOms\Dependency\Service\SalesOrderAmendmentOmsToSalesOrderAmendmentServiceInterface;

class ItemReservationUpdater implements ItemReservationUpdaterInterface
{
    /**
     * @param \Spryker\Zed\SalesOrderAmendmentOms\Dependency\Facade\SalesOrderAmendmentOmsToOmsFacadeInterface $omsFacade
     * @param \Spryker\Zed\SalesOrderAmendmentOms\Business\Reader\SalesOrderAmendmentQuoteReaderInterface $salesOrderAmendmentQuoteReader
     * @param \Spryker\Zed\SalesOrderAmendmentOms\Dependency\Service\SalesOrderAmendmentOmsToSalesOrderAmendmentServiceInterface $salesOrderAmendmentService
     */
    public function __construct(
        protected SalesOrderAmendmentOmsToOmsFacadeInterface $omsFacade,
        protected SalesOrderAmendmentQuoteReaderInterface $salesOrderAmendmentQuoteReader,
        protected SalesOrderAmendmentOmsToSalesOrderAmendmentServiceInterface $salesOrderAmendmentService
    ) {
    }

    /**
     * @param string $orderReference
     *
     * @return void
     */
    public function updateDeletedItemsReservations(string $orderReference): void
    {
        $salesOrderAmendmentQuoteTransfer = $this->salesOrderAmendmentQuoteReader
        ->findSalesOrderAmendmentQuoteByOrderReference($orderReference);

        if (!$salesOrderAmendmentQuoteTransfer) {
            return;
        }

        $quoteTransfer = $salesOrderAmendmentQuoteTransfer->getQuoteOrFail();

        $originalSalesOrderItemTransfersIndexedByGroupKey = $this->getOriginalSalesOrderItemTransfersIndexedByGroupKey($quoteTransfer);
        $salesOrderItemTransfersIndexedByGroupKey = $this->getSalesOrderItemTransfersIndexedByGroupKey($quoteTransfer);

        $deletedOriginalSalesOrderItemTransfers = array_diff_key($originalSalesOrderItemTransfersIndexedByGroupKey, $salesOrderItemTransfersIndexedByGroupKey);

        foreach ($deletedOriginalSalesOrderItemTransfers as $originalSalesOrderItemTransfer) {
            $this->omsFacade->updateReservation(
                (new ReservationRequestTransfer())->fromArray($originalSalesOrderItemTransfer->toArray(), true),
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array<string, \Generated\Shared\Transfer\OriginalSalesOrderItemTransfer>
     */
    protected function getOriginalSalesOrderItemTransfersIndexedByGroupKey(QuoteTransfer $quoteTransfer): array
    {
        $originalSalesOrderItemTransfersIndexedByGroupKey = [];
        foreach ($quoteTransfer->getOriginalSalesOrderItems() as $originalSalesOrderItemTransfer) {
            $originalSalesOrderItemTransfersIndexedByGroupKey[$originalSalesOrderItemTransfer->getGroupKeyOrFail()] = $originalSalesOrderItemTransfer;
        }

        return $originalSalesOrderItemTransfersIndexedByGroupKey;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array<string, \Generated\Shared\Transfer\ItemTransfer>
     */
    protected function getSalesOrderItemTransfersIndexedByGroupKey(QuoteTransfer $quoteTransfer): array
    {
        $salesOrderItemTransfersIndexedByGroupKey = [];
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $salesOrderItemTransfersIndexedByGroupKey[$this->salesOrderAmendmentService->buildOriginalSalesOrderItemGroupKey($itemTransfer)] = $itemTransfer;
        }

        return $salesOrderItemTransfersIndexedByGroupKey;
    }
}
