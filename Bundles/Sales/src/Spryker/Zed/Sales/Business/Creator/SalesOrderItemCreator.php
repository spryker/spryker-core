<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Creator;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SalesOrderItemCollectionRequestTransfer;
use Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\Sales\Business\ItemSaver\OrderItemsSaverInterface;
use Spryker\Zed\Sales\Business\Validator\SalesOrderItemValidatorInterface;
use Spryker\Zed\Sales\Persistence\SalesEntityManagerInterface;

class SalesOrderItemCreator implements SalesOrderItemCreatorInterface
{
    use TransactionTrait;

    /**
     * @param \Spryker\Zed\Sales\Persistence\SalesEntityManagerInterface $salesEntityManager
     * @param \Spryker\Zed\Sales\Business\Validator\SalesOrderItemValidatorInterface $salesOrderItemValidator
     * @param \Spryker\Zed\Sales\Business\ItemSaver\OrderItemsSaverInterface $orderItemsSaver
     * @param list<\Spryker\Zed\SalesExtension\Dependency\Plugin\SalesOrderItemsPreCreatePluginInterface> $salesOrderItemsPreCreatePlugins
     * @param list<\Spryker\Zed\SalesExtension\Dependency\Plugin\SalesOrderItemCollectionPostCreatePluginInterface> $salesOrderItemCollectionPostCreatePlugins
     */
    public function __construct(
        protected SalesEntityManagerInterface $salesEntityManager,
        protected SalesOrderItemValidatorInterface $salesOrderItemValidator,
        protected OrderItemsSaverInterface $orderItemsSaver,
        protected array $salesOrderItemsPreCreatePlugins,
        protected array $salesOrderItemCollectionPostCreatePlugins
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderItemCollectionRequestTransfer $salesOrderItemCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer
     */
    public function createSalesOrderItemCollectionByQuote(
        SalesOrderItemCollectionRequestTransfer $salesOrderItemCollectionRequestTransfer
    ): SalesOrderItemCollectionResponseTransfer {
        $this->assertRequiredFields($salesOrderItemCollectionRequestTransfer);
        $salesOrderItemCollectionResponseTransfer = (new SalesOrderItemCollectionResponseTransfer())
            ->setItems($salesOrderItemCollectionRequestTransfer->getItems());

        $salesOrderItemCollectionResponseTransfer = $this->salesOrderItemValidator->validate($salesOrderItemCollectionResponseTransfer);

        if ($salesOrderItemCollectionResponseTransfer->getErrors()->count()) {
            return $salesOrderItemCollectionResponseTransfer;
        }

        return $this->getTransactionHandler()->handleTransaction(function () use ($salesOrderItemCollectionRequestTransfer): SalesOrderItemCollectionResponseTransfer {
            return $this->executeCreateSalesOrderItemCollectionByQuoteTransaction($salesOrderItemCollectionRequestTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderItemCollectionRequestTransfer $salesOrderItemCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer
     */
    protected function executeCreateSalesOrderItemCollectionByQuoteTransaction(
        SalesOrderItemCollectionRequestTransfer $salesOrderItemCollectionRequestTransfer
    ): SalesOrderItemCollectionResponseTransfer {
        $quoteTransfer = $salesOrderItemCollectionRequestTransfer->getQuoteOrFail();
        $quoteTransfer->setItems($salesOrderItemCollectionRequestTransfer->getItems());

        $saveOrderTransfer = (new SaveOrderTransfer())
            ->setIdSalesOrder($quoteTransfer->getItems()->offsetGet(0)->getFkSalesOrderOrFail());

        $quoteTransfer = $this->executeSalesOrderItemsPreCreatePlugins($quoteTransfer, $saveOrderTransfer);

        $itemTransfers = $this->orderItemsSaver->saveOrderItems($quoteTransfer, $saveOrderTransfer)->getOrderItems();
        $salesOrderItemCollectionResponseTransfer = (new SalesOrderItemCollectionResponseTransfer())
            ->setItems($itemTransfers);

        return $this->executeSalesOrderItemCollectionPostCreatePlugins($salesOrderItemCollectionResponseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function executeSalesOrderItemsPreCreatePlugins(
        QuoteTransfer $quoteTransfer,
        SaveOrderTransfer $saveOrderTransfer
    ): QuoteTransfer {
        foreach ($this->salesOrderItemsPreCreatePlugins as $salesOrderItemsPreCreatePlugin) {
            $quoteTransfer = $salesOrderItemsPreCreatePlugin->preCreate($quoteTransfer, $saveOrderTransfer);
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer $salesOrderItemCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer
     */
    protected function executeSalesOrderItemCollectionPostCreatePlugins(
        SalesOrderItemCollectionResponseTransfer $salesOrderItemCollectionResponseTransfer
    ): SalesOrderItemCollectionResponseTransfer {
        foreach ($this->salesOrderItemCollectionPostCreatePlugins as $salesOrderItemCollectionPostCreatePlugin) {
            $salesOrderItemCollectionResponseTransfer = $salesOrderItemCollectionPostCreatePlugin->postCreate(
                $salesOrderItemCollectionResponseTransfer,
            );
        }

        return $salesOrderItemCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderItemCollectionRequestTransfer $salesOrderItemCollectionRequestTransfer
     *
     * @return void
     */
    protected function assertRequiredFields(
        SalesOrderItemCollectionRequestTransfer $salesOrderItemCollectionRequestTransfer
    ): void {
        $salesOrderItemCollectionRequestTransfer->requireQuote();
        foreach ($salesOrderItemCollectionRequestTransfer->getItems() as $itemTransfer) {
            $itemTransfer->requireFkSalesOrder();
        }
    }
}
