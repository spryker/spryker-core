<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPayment\Business\MessageEmitter;

use Generated\Shared\Transfer\CancelPaymentTransfer;
use Generated\Shared\Transfer\CapturePaymentTransfer;
use Generated\Shared\Transfer\EventPaymentTransfer;
use Generated\Shared\Transfer\OrderItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\RefundPaymentTransfer;
use Spryker\Zed\SalesPayment\Business\Calculator\CaptureAmountCalculatorInterface;
use Spryker\Zed\SalesPayment\Business\Calculator\RefundAmountCalculatorInterface;
use Spryker\Zed\SalesPayment\Business\Exception\EventExecutionForbiddenException;
use Spryker\Zed\SalesPayment\Business\Exception\OrderNotFoundException;
use Spryker\Zed\SalesPayment\Dependency\Facade\SalesPaymentToMessageBrokerFacadeInterface;
use Spryker\Zed\SalesPayment\Dependency\Facade\SalesPaymentToSalesFacadeInterface;
use Spryker\Zed\SalesPayment\SalesPaymentConfig;

class MessageEmitter implements MessageEmitterInterface
{
    /**
     * @var \Spryker\Zed\SalesPayment\Dependency\Facade\SalesPaymentToMessageBrokerFacadeInterface
     */
    protected $messageBrokerFacade;

    /**
     * @var \Spryker\Zed\SalesPayment\Dependency\Facade\SalesPaymentToSalesFacadeInterface
     */
    protected $salesFacade;

    /**
     * @var \Spryker\Zed\SalesPayment\SalesPaymentConfig
     */
    protected $salesPaymentConfig;

    /**
     * @var \Spryker\Zed\SalesPayment\Business\Calculator\CaptureAmountCalculatorInterface
     */
    protected $captureAmountCalculator;

    /**
     * @var \Spryker\Zed\SalesPayment\Business\Calculator\RefundAmountCalculatorInterface
     */
    protected $refundAmountCalculator;

    /**
     * @param \Spryker\Zed\SalesPayment\Dependency\Facade\SalesPaymentToMessageBrokerFacadeInterface $messageBrokerFacade
     * @param \Spryker\Zed\SalesPayment\Dependency\Facade\SalesPaymentToSalesFacadeInterface $salesFacade
     * @param \Spryker\Zed\SalesPayment\SalesPaymentConfig $salesPaymentConfig
     * @param \Spryker\Zed\SalesPayment\Business\Calculator\CaptureAmountCalculatorInterface $captureAmountCalculator
     * @param \Spryker\Zed\SalesPayment\Business\Calculator\RefundAmountCalculatorInterface $refundAmountCalculator
     */
    public function __construct(
        SalesPaymentToMessageBrokerFacadeInterface $messageBrokerFacade,
        SalesPaymentToSalesFacadeInterface $salesFacade,
        SalesPaymentConfig $salesPaymentConfig,
        CaptureAmountCalculatorInterface $captureAmountCalculator,
        RefundAmountCalculatorInterface $refundAmountCalculator
    ) {
        $this->messageBrokerFacade = $messageBrokerFacade;
        $this->salesFacade = $salesFacade;
        $this->salesPaymentConfig = $salesPaymentConfig;
        $this->captureAmountCalculator = $captureAmountCalculator;
        $this->refundAmountCalculator = $refundAmountCalculator;
    }

    /**
     * @param \Generated\Shared\Transfer\EventPaymentTransfer $eventPaymentTransfer
     *
     * @return void
     */
    public function sendCancelPaymentMessage(EventPaymentTransfer $eventPaymentTransfer): void
    {
        $orderTransfer = $this->getOrderTransfer($eventPaymentTransfer);

        $cancelPaymentTransfer = (new CancelPaymentTransfer())
            ->setOrderReference($orderTransfer->getOrderReference())
            ->setOrderItemIds($eventPaymentTransfer->getOrderItemIds())
            ->setCurrencyIsoCode($orderTransfer->getCurrencyIsoCode())
            ->setAmount(0);

        $this->messageBrokerFacade->sendMessage($cancelPaymentTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\EventPaymentTransfer $eventPaymentTransfer
     *
     * @throws \Spryker\Zed\SalesPayment\Business\Exception\EventExecutionForbiddenException
     *
     * @return void
     */
    public function sendCapturePaymentMessage(EventPaymentTransfer $eventPaymentTransfer): void
    {
        $orderTransfer = $this->getOrderTransfer($eventPaymentTransfer);

        $isCaptureProcessBlocked = $this->hasOrderAnyItemInStates(
            $orderTransfer,
            $this->salesPaymentConfig->getPaymentCaptureRequestBlockingStates(),
        );

        if ($isCaptureProcessBlocked) {
            throw new EventExecutionForbiddenException(sprintf('Capture can\'t be processed at this moment. OrderReference: %s', $orderTransfer->getOrderReference()));
        }

        $captureAmount = $this->captureAmountCalculator->getCaptureAmount($orderTransfer, $eventPaymentTransfer->getOrderItemIds());
        if ($captureAmount <= 0) {
            return;
        }

        $capturePaymentTransfer = (new CapturePaymentTransfer())
            ->setOrderReference($orderTransfer->getOrderReference())
            ->setOrderItemIds($eventPaymentTransfer->getOrderItemIds())
            ->setCurrencyIsoCode($orderTransfer->getCurrencyIsoCode())
            ->setAmount($captureAmount);

        $this->messageBrokerFacade->sendMessage($capturePaymentTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\EventPaymentTransfer $eventPaymentTransfer
     *
     * @throws \Spryker\Zed\SalesPayment\Business\Exception\EventExecutionForbiddenException
     *
     * @return void
     */
    public function sendRefundPaymentMessage(EventPaymentTransfer $eventPaymentTransfer): void
    {
        $orderTransfer = $this->getOrderTransfer($eventPaymentTransfer);
        $isRefundProcessBlocked = $this->hasOrderAnyItemInStates(
            $orderTransfer,
            $this->salesPaymentConfig->getPaymentRefundRequestBlockingStates(),
        );

        if ($isRefundProcessBlocked) {
            throw new EventExecutionForbiddenException(sprintf('Refund can\'t be processed at this moment. OrderReference: %s', $orderTransfer->getOrderReference()));
        }

        $refundAmount = $this->refundAmountCalculator->getRefundAmount($orderTransfer, $eventPaymentTransfer->getOrderItemIds());
        if ($refundAmount <= 0) {
            return;
        }

        $refundPaymentTransfer = (new RefundPaymentTransfer())
            ->setOrderReference($orderTransfer->getOrderReference())
            ->setOrderItemIds($eventPaymentTransfer->getOrderItemIds())
            ->setCurrencyIsoCode($orderTransfer->getCurrencyIsoCode())
            ->setAmount($refundAmount * -1);

        foreach ($orderTransfer->getItems() as $itemTransfer) {
            if (!in_array($itemTransfer->getIdSalesOrderItem(), $eventPaymentTransfer->getOrderItemIds(), true)) {
                continue;
            }

            $orderItemTransfer = (new OrderItemTransfer())
                ->setOrderItemId($itemTransfer->getIdSalesOrderItem())
                ->setSku($itemTransfer->getSku());

            $refundPaymentTransfer->addOrderItem($orderItemTransfer);
        }

        $this->messageBrokerFacade->sendMessage($refundPaymentTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\EventPaymentTransfer $eventPaymentTransfer
     *
     * @throws \Spryker\Zed\SalesPayment\Business\Exception\OrderNotFoundException
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function getOrderTransfer(EventPaymentTransfer $eventPaymentTransfer): OrderTransfer
    {
        $orderTransfer = $this->salesFacade->findOrderByIdSalesOrder($eventPaymentTransfer->getIdSalesOrderOrFail());

        if (!$orderTransfer) {
            throw new OrderNotFoundException();
        }

        return $orderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param array<string> $orderItemStates
     *
     * @return bool
     */
    protected function hasOrderAnyItemInStates(OrderTransfer $orderTransfer, array $orderItemStates): bool
    {
        foreach ($orderTransfer->getItems() as $itemTransfer) {
            if (in_array($itemTransfer->getStateOrFail()->getName(), $orderItemStates, true)) {
                return true;
            }
        }

        return false;
    }
}
