<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendmentOms\Business\Updater;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ReservationRequestTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentQuoteConditionsTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentQuoteCriteriaTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentQuoteTransfer;
use Spryker\Zed\SalesOrderAmendmentOms\Dependency\Facade\SalesOrderAmendmentOmsToOmsFacadeInterface;
use Spryker\Zed\SalesOrderAmendmentOms\Dependency\Facade\SalesOrderAmendmentOmsToSalesOrderAmendmentFacadeInterface;
use Spryker\Zed\SalesOrderAmendmentOms\Dependency\Service\SalesOrderAmendmentOmsToSalesOrderAmendmentServiceInterface;

class ItemReservationUpdater implements ItemReservationUpdaterInterface
{
    /**
     * @param \Spryker\Zed\SalesOrderAmendmentOms\Dependency\Facade\SalesOrderAmendmentOmsToOmsFacadeInterface $omsFacade
     * @param \Spryker\Zed\SalesOrderAmendmentOms\Dependency\Facade\SalesOrderAmendmentOmsToSalesOrderAmendmentFacadeInterface $salesOrderAmendmentFacade
     * @param \Spryker\Zed\SalesOrderAmendmentOms\Dependency\Service\SalesOrderAmendmentOmsToSalesOrderAmendmentServiceInterface $salesOrderAmendmentService
     */
    public function __construct(
        protected SalesOrderAmendmentOmsToOmsFacadeInterface $omsFacade,
        protected SalesOrderAmendmentOmsToSalesOrderAmendmentFacadeInterface $salesOrderAmendmentFacade,
        protected SalesOrderAmendmentOmsToSalesOrderAmendmentServiceInterface $salesOrderAmendmentService
    ) {
    }

    /**
     * @param string $orderReference
     *
     * @return void
     */
    public function updateDeletedItemsReservations(
        string $orderReference
    ): void {
        $salesOrderAmendmentQuoteTransfer = $this->findSalesOrderAmendmentQuote($orderReference);

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

    /**
     * @param string $orderReference
     *
     * @return \Generated\Shared\Transfer\SalesOrderAmendmentQuoteTransfer|null
     */
    protected function findSalesOrderAmendmentQuote(string $orderReference): ?SalesOrderAmendmentQuoteTransfer
    {
        $salesOrderAmendmentQuoteCollectionTransfer = $this->salesOrderAmendmentFacade->getSalesOrderAmendmentQuoteCollection(
            (new SalesOrderAmendmentQuoteCriteriaTransfer())->setSalesOrderAmendmentQuoteConditions(
                (new SalesOrderAmendmentQuoteConditionsTransfer())->addAmendmentOrderReference(
                    $orderReference,
                ),
            ),
        );

        if ($salesOrderAmendmentQuoteCollectionTransfer->getSalesOrderAmendmentQuotes()->offsetExists(0)) {
            return $salesOrderAmendmentQuoteCollectionTransfer->getSalesOrderAmendmentQuotes()->offsetGet(0);
        }

        return null;
    }
}
