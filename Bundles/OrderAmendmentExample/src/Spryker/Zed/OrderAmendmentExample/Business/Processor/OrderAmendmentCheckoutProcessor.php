<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OrderAmendmentExample\Business\Processor;

use Exception;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ErrorTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderItemFilterTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentQuoteCollectionRequestTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentQuoteTransfer;
use Spryker\Shared\SalesOrderAmendmentExtension\SalesOrderAmendmentExtensionContextsInterface;
use Spryker\Zed\OrderAmendmentExample\Business\Reader\SalesOrderAmendmentQuoteReaderInterface;
use Spryker\Zed\OrderAmendmentExample\Dependency\Facade\OrderAmendmentExampleToCheckoutFacadeInterface;
use Spryker\Zed\OrderAmendmentExample\Dependency\Facade\OrderAmendmentExampleToSalesFacadeInterface;
use Spryker\Zed\OrderAmendmentExample\Dependency\Facade\OrderAmendmentExampleToSalesOrderAmendmentFacadeInterface;

class OrderAmendmentCheckoutProcessor implements OrderAmendmentCheckoutProcessorInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_ERROR_APPLY_ORDER_AMENDMENT_DRAFT_FAILED = 'sales_order_amendment_oms.error.apply_order_amendment_draft_failed';

    /**
     * @uses \Spryker\Zed\Oms\Business\OrderStateMachine\OrderStateMachine::RETURN_DATA_UPDATED_ORDER_ITEMS
     *
     * @var string
     */
    protected const RETURN_DATA_UPDATED_ORDER_ITEMS = 'updatedOrderItems';

    /**
     * @var string
     */
    protected const ORDER_AMENDMENT_ASYNC_ORDER_ITEM_INITIAL_STATE = 'order amendment draft applied';

    /**
     * @param \Spryker\Zed\OrderAmendmentExample\Business\Reader\SalesOrderAmendmentQuoteReaderInterface $salesOrderAmendmentQuoteReader
     * @param \Spryker\Zed\OrderAmendmentExample\Dependency\Facade\OrderAmendmentExampleToSalesOrderAmendmentFacadeInterface $salesOrderAmendmentFacade
     * @param \Spryker\Zed\OrderAmendmentExample\Dependency\Facade\OrderAmendmentExampleToSalesFacadeInterface $salesFacade
     * @param \Spryker\Zed\OrderAmendmentExample\Dependency\Facade\OrderAmendmentExampleToCheckoutFacadeInterface $checkoutFacade
     */
    public function __construct(
        protected SalesOrderAmendmentQuoteReaderInterface $salesOrderAmendmentQuoteReader,
        protected OrderAmendmentExampleToSalesOrderAmendmentFacadeInterface $salesOrderAmendmentFacade,
        protected OrderAmendmentExampleToSalesFacadeInterface $salesFacade,
        protected OrderAmendmentExampleToCheckoutFacadeInterface $checkoutFacade
    ) {
    }

    /**
     * @param array<\Orm\Zed\Sales\Persistence\SpySalesOrderItem> $orderItems
     * @param string $orderReference
     * @param int $idSalesOrder
     *
     * @return array<mixed>
     */
    public function processOrderAmendmentCheckout(array $orderItems, string $orderReference, int $idSalesOrder): array
    {
        $salesOrderAmendmentQuoteTransfer = $this->salesOrderAmendmentQuoteReader
            ->findSalesOrderAmendmentQuoteByOrderReference($orderReference);

        if (!$salesOrderAmendmentQuoteTransfer) {
            return [];
        }

        $quoteTransfer = $this->prepareQuoteForCheckout($salesOrderAmendmentQuoteTransfer);
        $checkoutResponseTransfer = $this->placeOrder(clone $quoteTransfer);

        if (!$checkoutResponseTransfer->getIsSuccess()) {
            $this->handleFailedCheckout($quoteTransfer, $salesOrderAmendmentQuoteTransfer, $checkoutResponseTransfer);
        }

        return [static::RETURN_DATA_UPDATED_ORDER_ITEMS => $this->getFilteredItems($orderItems, $idSalesOrder)];
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentQuoteTransfer $salesOrderAmendmentQuoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function prepareQuoteForCheckout(SalesOrderAmendmentQuoteTransfer $salesOrderAmendmentQuoteTransfer): QuoteTransfer
    {
        $quoteTransfer = $salesOrderAmendmentQuoteTransfer->getQuoteOrFail();
        $quoteTransfer->setShouldSkipStateMachineRun(true)
            ->setDefaultOmsOrderItemState(static::ORDER_AMENDMENT_ASYNC_ORDER_ITEM_INITIAL_STATE);
        $quoteTransfer->getQuoteProcessFlowOrFail()
            ->setName(SalesOrderAmendmentExtensionContextsInterface::CONTEXT_ORDER_AMENDMENT);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    protected function placeOrder(QuoteTransfer $quoteTransfer): CheckoutResponseTransfer
    {
        try {
            return $this->checkoutFacade->placeOrder($quoteTransfer);
        } catch (Exception $e) {
            return (new CheckoutResponseTransfer())->setIsSuccess(false);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentQuoteTransfer $salesOrderAmendmentQuoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    protected function handleFailedCheckout(
        QuoteTransfer $quoteTransfer,
        SalesOrderAmendmentQuoteTransfer $salesOrderAmendmentQuoteTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer
    ): void {
        if ($checkoutResponseTransfer->getErrors()->count() > 0) {
            foreach ($checkoutResponseTransfer->getErrors() as $checkoutErrorTransfer) {
                $quoteTransfer->addError((new ErrorTransfer())->setMessage($checkoutErrorTransfer->getMessage()));
            }
        } else {
            $quoteTransfer->addError(
                (new ErrorTransfer())->setMessage(
                    static::GLOSSARY_KEY_ERROR_APPLY_ORDER_AMENDMENT_DRAFT_FAILED,
                ),
            );
        }

        $salesOrderAmendmentQuoteTransfer->setQuote($quoteTransfer);
        $this->salesOrderAmendmentFacade->updateSalesOrderAmendmentQuoteCollection(
            (new SalesOrderAmendmentQuoteCollectionRequestTransfer())->addSalesOrderAmendmentQuote($salesOrderAmendmentQuoteTransfer),
        );
    }

    /**
     * @param array<\Orm\Zed\Sales\Persistence\SpySalesOrderItem> $orderItems
     * @param int $idSalesOrder
     *
     * @return array<\Orm\Zed\Sales\Persistence\SpySalesOrderItem>
     */
    protected function getFilteredItems(array $orderItems, int $idSalesOrder): array
    {
        $orderItemFilterTransfer = (new OrderItemFilterTransfer())->addSalesOrderId($idSalesOrder);
        $itemCollectionTransfer = $this->salesFacade->getOrderItems($orderItemFilterTransfer);
        $updatedOrderItemsIds = array_map(
            static fn (ItemTransfer $itemTransfer): int => $itemTransfer->getIdSalesOrderItemOrFail(),
            $itemCollectionTransfer->getItems()->getArrayCopy(),
        );
        foreach ($orderItems as $key => $orderItem) {
            if (!in_array($orderItem->getIdSalesOrderItem(), $updatedOrderItemsIds, true)) {
                unset($orderItems[$key]);
            }
        }

        return $orderItems;
    }
}
