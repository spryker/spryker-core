<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendmentOms\Business\Triggerer;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\SalesOrderAmendmentOms\Business\Reader\OrderReaderInterface;
use Spryker\Zed\SalesOrderAmendmentOms\Dependency\Facade\SalesOrderAmendmentOmsToOmsFacadeInterface;
use Spryker\Zed\SalesOrderAmendmentOms\SalesOrderAmendmentOmsConfig;

class OmsEventTriggerer implements OmsEventTriggererInterface
{
    /**
     * @param \Spryker\Zed\SalesOrderAmendmentOms\Business\Reader\OrderReaderInterface $orderReader
     * @param \Spryker\Zed\SalesOrderAmendmentOms\SalesOrderAmendmentOmsConfig $salesOrderAmendmentOmsConfig
     * @param \Spryker\Zed\SalesOrderAmendmentOms\Dependency\Facade\SalesOrderAmendmentOmsToOmsFacadeInterface $omsFacade
     */
    public function __construct(
        protected OrderReaderInterface $orderReader,
        protected SalesOrderAmendmentOmsConfig $salesOrderAmendmentOmsConfig,
        protected SalesOrderAmendmentOmsToOmsFacadeInterface $omsFacade
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array<mixed>|null
     */
    public function triggerStartOrderAmendmentEvent(QuoteTransfer $quoteTransfer): ?array
    {
        return $this->triggerOrderAmendmentEventByName(
            $quoteTransfer,
            $this->salesOrderAmendmentOmsConfig->getStartOrderAmendmentEvent(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array<mixed>|null
     */
    public function triggerCancelOrderAmendmentEvent(QuoteTransfer $quoteTransfer): ?array
    {
        return $this->triggerOrderAmendmentEventByName(
            $quoteTransfer,
            $this->salesOrderAmendmentOmsConfig->getCancelOrderAmendmentEvent(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array<mixed>|null
     */
    public function triggerFinishSalesOrderAmendmentEvent(QuoteTransfer $quoteTransfer): ?array
    {
        return $this->triggerOrderAmendmentEventByName(
            $quoteTransfer,
            $this->salesOrderAmendmentOmsConfig->getFinishOrderAmendmentEvent(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array<mixed>|null
     */
    public function triggerStartOrderAmendmentDraftEvent(QuoteTransfer $quoteTransfer): ?array
    {
        return $this->triggerOrderAmendmentEventByName(
            $quoteTransfer,
            $this->salesOrderAmendmentOmsConfig->getStartOrderAmendmentDraftEvent(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $eventName
     *
     * @return array<mixed>|null
     */
    protected function triggerOrderAmendmentEventByName(QuoteTransfer $quoteTransfer, string $eventName): ?array
    {
        $orderTransfer = $this->orderReader->findOrderByOrderReference(
            $quoteTransfer->getAmendmentOrderReferenceOrFail(),
        );
        if ($orderTransfer === null) {
            return null;
        }

        return $this->omsFacade->triggerEventForOrderItems(
            $eventName,
            $this->extractSalesOrderItemIds($orderTransfer),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return list<int>
     */
    protected function extractSalesOrderItemIds(OrderTransfer $orderTransfer): array
    {
        $salesOrderItemIds = [];
        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $salesOrderItemIds[] = $itemTransfer->getIdSalesOrderItemOrFail();
        }

        return $salesOrderItemIds;
    }
}
