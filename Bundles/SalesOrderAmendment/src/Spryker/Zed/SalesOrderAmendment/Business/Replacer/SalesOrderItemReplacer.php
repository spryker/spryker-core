<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendment\Business\Replacer;

use ArrayObject;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentItemCollectionTransfer;
use Generated\Shared\Transfer\SalesOrderItemCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\SalesOrderItemCollectionRequestTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\SalesOrderAmendment\Business\Strategy\SalesOrderAmendmentItemCollectorStrategyInterface;
use Spryker\Zed\SalesOrderAmendment\Dependency\Facade\SalesOrderAmendmentToSalesFacadeInterface;

class SalesOrderItemReplacer implements SalesOrderItemReplacerInterface
{
    use TransactionTrait;

    /**
     * @param \Spryker\Zed\SalesOrderAmendment\Business\Strategy\SalesOrderAmendmentItemCollectorStrategyInterface $defaultSalesOrderAmendmentItemCollectorStrategy
     * @param \Spryker\Zed\SalesOrderAmendment\Dependency\Facade\SalesOrderAmendmentToSalesFacadeInterface $salesFacade
     * @param list<\Spryker\Zed\SalesOrderAmendmentExtension\Dependency\Plugin\SalesOrderAmendmentItemCollectorStrategyPluginInterface> $salesOrderAmendmentItemCollectorStrategyPlugins
     * @param list<\Spryker\Zed\SalesOrderAmendmentExtension\Dependency\Plugin\SalesOrderItemCollectorPluginInterface> $salesOrderItemCollectorPlugins
     */
    public function __construct(
        protected SalesOrderAmendmentItemCollectorStrategyInterface $defaultSalesOrderAmendmentItemCollectorStrategy,
        protected SalesOrderAmendmentToSalesFacadeInterface $salesFacade,
        protected array $salesOrderAmendmentItemCollectorStrategyPlugins,
        protected array $salesOrderItemCollectorPlugins
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function replaceSalesOrderItems(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer): void
    {
        $orderTransfer = $quoteTransfer->getOriginalOrderOrFail();
        $salesOrderAmendmentItemCollectionTransfer = $this->getSalesOrderAmendmentItemsCollection($quoteTransfer, $orderTransfer);
        $salesOrderAmendmentItemCollectionTransfer = $this->executeSalesOrderItemCollectorPlugins(
            $orderTransfer,
            $salesOrderAmendmentItemCollectionTransfer,
        );

        $saveOrderTransfer->setOrderItems($salesOrderAmendmentItemCollectionTransfer->getItemsToSkip());

        $this->getTransactionHandler()->handleTransaction(function () use ($salesOrderAmendmentItemCollectionTransfer, $quoteTransfer, $orderTransfer, $saveOrderTransfer): void {
            $this->executeReplaceSalesOrderItemsTransaction(
                $salesOrderAmendmentItemCollectionTransfer,
                $quoteTransfer,
                $orderTransfer,
                $saveOrderTransfer,
            );
        });
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentItemCollectionTransfer $salesOrderAmendmentItemCollectionTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    protected function executeReplaceSalesOrderItemsTransaction(
        SalesOrderAmendmentItemCollectionTransfer $salesOrderAmendmentItemCollectionTransfer,
        QuoteTransfer $quoteTransfer,
        OrderTransfer $orderTransfer,
        SaveOrderTransfer $saveOrderTransfer
    ): void {
        if ($salesOrderAmendmentItemCollectionTransfer->getItemsToCreate()->count() > 0) {
            $saveOrderTransfer = $this->createOrderItems(
                $salesOrderAmendmentItemCollectionTransfer->getItemsToCreate(),
                $quoteTransfer,
                $orderTransfer,
                $saveOrderTransfer,
            );
        }

        if ($salesOrderAmendmentItemCollectionTransfer->getItemsToUpdate()->count() > 0) {
            $saveOrderTransfer = $this->updateOrderItems(
                $salesOrderAmendmentItemCollectionTransfer->getItemsToUpdate(),
                $quoteTransfer,
                $orderTransfer,
                $saveOrderTransfer,
            );
        }

        if ($salesOrderAmendmentItemCollectionTransfer->getItemsToDelete()->count() > 0) {
            $this->deleteOrderItems($salesOrderAmendmentItemCollectionTransfer->getItemsToDelete());
        }
    }

    /**
     * @param \ArrayObject<int,\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    protected function createOrderItems(
        ArrayObject $itemTransfers,
        QuoteTransfer $quoteTransfer,
        OrderTransfer $orderTransfer,
        SaveOrderTransfer $saveOrderTransfer
    ): SaveOrderTransfer {
        $salesOrderItemCollectionRequestTransfer = $this->createSalesOrderItemCollectionRequestTransfer(
            $itemTransfers,
            $quoteTransfer,
            $orderTransfer,
        );

        $salesOrderItemCollectionResponseTransfer = $this->salesFacade
            ->createSalesOrderItemCollectionByQuote($salesOrderItemCollectionRequestTransfer);

        foreach ($salesOrderItemCollectionResponseTransfer->getItems() as $itemTransfer) {
            $saveOrderTransfer->addOrderItem($itemTransfer);
        }

        return $saveOrderTransfer;
    }

    /**
     * @param \ArrayObject<int,\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    protected function updateOrderItems(
        ArrayObject $itemTransfers,
        QuoteTransfer $quoteTransfer,
        OrderTransfer $orderTransfer,
        SaveOrderTransfer $saveOrderTransfer
    ): SaveOrderTransfer {
        $salesOrderItemCollectionRequestTransfer = $this->createSalesOrderItemCollectionRequestTransfer(
            $itemTransfers,
            $quoteTransfer,
            $orderTransfer,
        );

        $salesOrderItemCollectionResponseTransfer = $this->salesFacade->updateSalesOrderItemCollectionByQuote($salesOrderItemCollectionRequestTransfer);

        foreach ($salesOrderItemCollectionResponseTransfer->getItems() as $itemTransfer) {
            $saveOrderTransfer->addOrderItem($itemTransfer);
        }

        return $saveOrderTransfer;
    }

    /**
     * @param \ArrayObject<int,\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return void
     */
    protected function deleteOrderItems(ArrayObject $itemTransfers): void
    {
        $salesOrderItemCollectionDeleteCriteriaTransfer = (new SalesOrderItemCollectionDeleteCriteriaTransfer())
            ->setItems($itemTransfers);

        $this->salesFacade->deleteSalesOrderItemCollection($salesOrderItemCollectionDeleteCriteriaTransfer);
    }

    /**
     * @param \ArrayObject<int,\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemCollectionRequestTransfer
     */
    protected function createSalesOrderItemCollectionRequestTransfer(
        ArrayObject $itemTransfers,
        QuoteTransfer $quoteTransfer,
        OrderTransfer $orderTransfer
    ): SalesOrderItemCollectionRequestTransfer {
        foreach ($itemTransfers as $itemTransfer) {
            if ($itemTransfer->getFkSalesOrder() === null) {
                $itemTransfer->setFkSalesOrder($orderTransfer->getIdSalesOrderOrFail());
            }
        }

        return (new SalesOrderItemCollectionRequestTransfer())
            ->setItems($itemTransfers)
            ->setQuote(clone $quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderAmendmentItemCollectionTransfer
     */
    protected function getSalesOrderAmendmentItemsCollection(
        QuoteTransfer $quoteTransfer,
        OrderTransfer $orderTransfer
    ): SalesOrderAmendmentItemCollectionTransfer {
        foreach ($this->salesOrderAmendmentItemCollectorStrategyPlugins as $salesOrderAmendmentItemCollectorStrategyPlugin) {
            if ($salesOrderAmendmentItemCollectorStrategyPlugin->isApplicable($quoteTransfer, $orderTransfer)) {
                return $salesOrderAmendmentItemCollectorStrategyPlugin->collect($quoteTransfer, $orderTransfer);
            }
        }

        return $this->defaultSalesOrderAmendmentItemCollectorStrategy->collect($quoteTransfer, $orderTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentItemCollectionTransfer $salesOrderAmendmentItemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderAmendmentItemCollectionTransfer
     */
    protected function executeSalesOrderItemCollectorPlugins(
        OrderTransfer $orderTransfer,
        SalesOrderAmendmentItemCollectionTransfer $salesOrderAmendmentItemCollectionTransfer
    ): SalesOrderAmendmentItemCollectionTransfer {
        foreach ($this->salesOrderItemCollectorPlugins as $salesOrderItemCollectorPlugin) {
            $salesOrderAmendmentItemCollectionTransfer = $salesOrderItemCollectorPlugin->collect(
                $orderTransfer,
                $salesOrderAmendmentItemCollectionTransfer,
            );
        }

        return $salesOrderAmendmentItemCollectionTransfer;
    }
}
