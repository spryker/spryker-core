<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Writer;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\OrderCancelRequestTransfer;
use Generated\Shared\Transfer\OrderCancelResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Sales\Business\Order\OrderReaderInterface;
use Spryker\Zed\Sales\Business\Triggerer\OmsEventTriggererInterface;

class OrderWriter implements OrderWriterInterface
{
    protected const GLOSSARY_KEY_CUSTOMER_ORDER_NOT_FOUND = 'sales.error.customer_order_not_found';
    protected const GLOSSARY_KEY_ORDER_CANNOT_BE_CANCELLED = 'sales.error.order_cannot_be_canceled_due_to_wrong_item_state';

    /**
     * @var \Spryker\Zed\Sales\Business\Triggerer\OmsEventTriggererInterface
     */
    protected $omsEventTriggerer;

    /**
     * @var \Spryker\Zed\Sales\Business\Order\OrderReaderInterface
     */
    protected $orderReader;

    /**
     * @param \Spryker\Zed\Sales\Business\Triggerer\OmsEventTriggererInterface $omsEventTriggerer
     * @param \Spryker\Zed\Sales\Business\Order\OrderReaderInterface $orderReader
     */
    public function __construct(
        OmsEventTriggererInterface $omsEventTriggerer,
        OrderReaderInterface $orderReader
    ) {
        $this->omsEventTriggerer = $omsEventTriggerer;
        $this->orderReader = $orderReader;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderCancelRequestTransfer $orderCancelRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OrderCancelResponseTransfer
     */
    public function cancelOrder(OrderCancelRequestTransfer $orderCancelRequestTransfer): OrderCancelResponseTransfer
    {
        $orderCancelRequestTransfer->requireIdSalesOrder();
        $orderTransfer = $this->orderReader->findOrderByIdSalesOrder($orderCancelRequestTransfer->getIdSalesOrder());

        if (!$orderTransfer || !$this->isApplicableForCustomer($orderCancelRequestTransfer, $orderTransfer)) {
            return $this->getErrorResponse(static::GLOSSARY_KEY_CUSTOMER_ORDER_NOT_FOUND);
        }

        if (!$orderTransfer->getIsCancellable()) {
            return $this->getErrorResponse(static::GLOSSARY_KEY_ORDER_CANNOT_BE_CANCELLED);
        }

        $this->omsEventTriggerer->triggerOrderItemsCancelEvent($orderTransfer);

        return (new OrderCancelResponseTransfer())
            ->setIsSuccessful(true)
            ->setOrder($this->orderReader->findOrderByIdSalesOrder($orderCancelRequestTransfer->getIdSalesOrder()));
    }

    /**
     * @param \Generated\Shared\Transfer\OrderCancelRequestTransfer $orderCancelRequestTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    protected function isApplicableForCustomer(
        OrderCancelRequestTransfer $orderCancelRequestTransfer,
        OrderTransfer $orderTransfer
    ): bool {
        $customerReference = $this->extractCustomerReference($orderCancelRequestTransfer);

        return !$customerReference || $orderTransfer->getCustomerReference() === $customerReference;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderCancelRequestTransfer $orderCancelRequestTransfer
     *
     * @return string|null
     */
    protected function extractCustomerReference(OrderCancelRequestTransfer $orderCancelRequestTransfer): ?string
    {
        if (!$orderCancelRequestTransfer->getCustomer()) {
            return null;
        }

        return $orderCancelRequestTransfer->getCustomer()->getCustomerReference();
    }

    /**
     * @param string $message
     *
     * @return \Generated\Shared\Transfer\OrderCancelResponseTransfer
     */
    protected function getErrorResponse(string $message): OrderCancelResponseTransfer
    {
        $messageTransfer = (new MessageTransfer())
            ->setValue($message);

        return (new OrderCancelResponseTransfer())
            ->setIsSuccessful(false)
            ->addMessage($messageTransfer);
    }
}
