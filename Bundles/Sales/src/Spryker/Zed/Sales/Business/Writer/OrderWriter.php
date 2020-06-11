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
use Spryker\Zed\Sales\Business\Model\Order\OrderRepositoryReaderInterface;
use Spryker\Zed\Sales\Business\Triggerer\OmsEventTriggererInterface;

class OrderWriter implements OrderWriterInterface
{
    protected const GLOSSARY_KEY_CUSTOMER_ORDER_NOT_FOUND = 'sales.error.customer_order_not_found';
    protected const GLOSSARY_KEY_ORDER_CANNOT_BE_CANCELLED = 'sales.error.order_cannot_be_canceled_due_to_wrong_item_state';

    /**
     * @var \Spryker\Zed\Sales\Business\Model\Order\OrderRepositoryReaderInterface
     */
    protected $orderRepositoryReader;

    /**
     * @var \Spryker\Zed\Sales\Business\Triggerer\OmsEventTriggererInterface
     */
    protected $omsEventTriggerer;

    /**
     * @param \Spryker\Zed\Sales\Business\Model\Order\OrderRepositoryReaderInterface $orderRepositoryReader
     * @param \Spryker\Zed\Sales\Business\Triggerer\OmsEventTriggererInterface $omsEventTriggerer
     */
    public function __construct(
        OrderRepositoryReaderInterface $orderRepositoryReader,
        OmsEventTriggererInterface $omsEventTriggerer
    ) {
        $this->orderRepositoryReader = $orderRepositoryReader;
        $this->omsEventTriggerer = $omsEventTriggerer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderCancelRequestTransfer $orderCancelRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OrderCancelResponseTransfer
     */
    public function cancelOrder(OrderCancelRequestTransfer $orderCancelRequestTransfer): OrderCancelResponseTransfer
    {
        $orderTransfer = $this->findCustomerOrder($orderCancelRequestTransfer);

        if (!$orderTransfer) {
            return $this->getErrorResponse(static::GLOSSARY_KEY_CUSTOMER_ORDER_NOT_FOUND);
        }

        if (!$orderTransfer->getIsCancellable()) {
            return $this->getErrorResponse(static::GLOSSARY_KEY_ORDER_CANNOT_BE_CANCELLED);
        }

        $this->omsEventTriggerer->triggerOrderItemsCancelEvent($orderTransfer);

        return (new OrderCancelResponseTransfer())
            ->setIsSuccessful(true)
            ->setOrder($this->findCustomerOrder($orderCancelRequestTransfer));
    }

    /**
     * @param \Generated\Shared\Transfer\OrderCancelRequestTransfer $orderCancelRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer|null
     */
    protected function findCustomerOrder(OrderCancelRequestTransfer $orderCancelRequestTransfer): ?OrderTransfer
    {
        $orderCancelRequestTransfer
            ->requireOrderReference()
            ->requireCustomer()
            ->getCustomer()
                ->requireCustomerReference();

        $orderTransfer = (new OrderTransfer())
            ->setOrderReference($orderCancelRequestTransfer->getOrderReference())
            ->setCustomerReference($orderCancelRequestTransfer->getCustomer()->getCustomerReference());

        $orderTransfer = $this->orderRepositoryReader->getCustomerOrderByOrderReference($orderTransfer);

        return $orderTransfer->getIdSalesOrder() ? $orderTransfer : null;
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
