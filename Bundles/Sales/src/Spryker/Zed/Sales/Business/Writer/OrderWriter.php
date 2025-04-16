<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\Sales\Business\Writer;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\OmsEventTriggerResponseTransfer;
use Generated\Shared\Transfer\OrderCancelRequestTransfer;
use Generated\Shared\Transfer\OrderCancelResponseTransfer;
use Generated\Shared\Transfer\OrderConditionsTransfer;
use Generated\Shared\Transfer\OrderCriteriaTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use Spryker\Zed\Sales\Business\Order\OrderReaderInterface;
use Spryker\Zed\Sales\Business\Triggerer\OmsEventTriggererInterface;
use Spryker\Zed\Sales\Persistence\SalesRepositoryInterface;

class OrderWriter implements OrderWriterInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_CUSTOMER_ORDER_NOT_FOUND = 'sales.error.customer_order_not_found';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_ORDER_CANNOT_BE_CANCELLED = 'sales.error.order_cannot_be_canceled_due_to_wrong_item_state';

    /**
     * @uses \Spryker\Zed\Oms\OmsConfig::OMS_EVENT_TRIGGER_RESPONSE
     *
     * @var string
     */
    protected const OMS_EVENT_TRIGGER_RESPONSE = 'oms_event_trigger_response';

    /**
     * @var \Spryker\Zed\Sales\Business\Triggerer\OmsEventTriggererInterface
     */
    protected $omsEventTriggerer;

    /**
     * @var \Spryker\Zed\Sales\Business\Order\OrderReaderInterface
     */
    protected $orderReader;

    /**
     * @var \Spryker\Zed\Sales\Persistence\SalesRepositoryInterface
     */
    protected SalesRepositoryInterface $salesRepository;

    /**
     * @var list<\Spryker\Zed\SalesExtension\Dependency\Plugin\OrderPostCancelPluginInterface>
     */
    protected array $orderPostCancelPlugins;

    /**
     * @param \Spryker\Zed\Sales\Business\Triggerer\OmsEventTriggererInterface $omsEventTriggerer
     * @param \Spryker\Zed\Sales\Business\Order\OrderReaderInterface $orderReader
     * @param \Spryker\Zed\Sales\Persistence\SalesRepositoryInterface $salesRepository
     * @param list<\Spryker\Zed\SalesExtension\Dependency\Plugin\OrderPostCancelPluginInterface> $orderPostCancelPlugins
     */
    public function __construct(
        OmsEventTriggererInterface $omsEventTriggerer,
        OrderReaderInterface $orderReader,
        SalesRepositoryInterface $salesRepository,
        array $orderPostCancelPlugins
    ) {
        $this->omsEventTriggerer = $omsEventTriggerer;
        $this->orderReader = $orderReader;
        $this->salesRepository = $salesRepository;
        $this->orderPostCancelPlugins = $orderPostCancelPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderCancelRequestTransfer $orderCancelRequestTransfer
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return \Generated\Shared\Transfer\OrderCancelResponseTransfer
     */
    public function cancelOrder(OrderCancelRequestTransfer $orderCancelRequestTransfer): OrderCancelResponseTransfer
    {
        // This field will be set on purpose, when null, we have to check the customer as cancelOrder should have a customer for BC reasons.
        // The possibility to cancel an order without a customer is added to be able to cancelOrders when a customer or guest customer is on a hosted payment page and uses the back button to change something in the cart.
        // Without this possibility, it wouldn't be possible to order an item that was ordered before by the same customer when it was the last order item in stock
        if ($orderCancelRequestTransfer->getAllowCancellationWithoutCustomer() === null) {
                $orderCancelRequestTransfer
                    ->requireCustomer()
                    ->getCustomer()
                        ->requireCustomerReference();
        }

        if ($orderCancelRequestTransfer->getIdSalesOrder() === null && $orderCancelRequestTransfer->getOrderReference() === null) {
            throw new RequiredTransferPropertyException(
                'OrderCancelRequestTransfer.idSalesOrder or OrderCancelRequestTransfer.orderReference and customer are required for cancelOrder',
            );
        }

        $idSalesOrder = $orderCancelRequestTransfer->getIdSalesOrder();

        if (!$orderCancelRequestTransfer->getAllowCancellationWithoutCustomer() && $idSalesOrder === null) {
            $idSalesOrder = (int)$this->salesRepository->findCustomerOrderIdByOrderReference(
                (string)$orderCancelRequestTransfer->getCustomerOrFail()->getCustomerReferenceOrFail(),
                $orderCancelRequestTransfer->getOrderReferenceOrFail(),
            );
        }

        $orderConditionsTransfer = new OrderConditionsTransfer();

        if ($orderCancelRequestTransfer->getIdSalesOrder()) {
            $orderConditionsTransfer->addIdSalesOrder($idSalesOrder);
        }

        if ($orderCancelRequestTransfer->getOrderReference()) {
            $orderConditionsTransfer->addOrderReference($orderCancelRequestTransfer->getOrderReference());
        }

        $orderCriteriaTransfer = new OrderCriteriaTransfer();
        $orderCriteriaTransfer
            ->setOrderConditions($orderConditionsTransfer);

        $orderTransfer = $this->orderReader->findOrderByOrderCriteria($orderCriteriaTransfer);

        if (!$orderTransfer || !$this->isApplicableForCustomer($orderCancelRequestTransfer, $orderTransfer)) {
            return $this->getErrorResponse(static::GLOSSARY_KEY_CUSTOMER_ORDER_NOT_FOUND);
        }

        if (!$orderTransfer->getIsCancellable()) {
            return $this->getErrorResponse(static::GLOSSARY_KEY_ORDER_CANNOT_BE_CANCELLED);
        }

        $triggerEventReturnData = $this->omsEventTriggerer->triggerOrderItemsCancelEvent($orderTransfer);
        $omsEventTriggerResponseTransfer = $triggerEventReturnData[static::OMS_EVENT_TRIGGER_RESPONSE] ?? null;

        if (
            $omsEventTriggerResponseTransfer instanceof OmsEventTriggerResponseTransfer
            && $omsEventTriggerResponseTransfer->getIsSuccessful() === false
        ) {
            return (new OrderCancelResponseTransfer())
                ->setIsSuccessful(false)
                ->setMessages($omsEventTriggerResponseTransfer->getMessages());
        }

        $updatedOrderTransfer = $this->executeOrderPostCancelPlugins(
            $this->orderReader->findOrderByOrderCriteria($orderCriteriaTransfer),
        );

        return (new OrderCancelResponseTransfer())
            ->setIsSuccessful(true)
            ->setOrder($updatedOrderTransfer);
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

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function executeOrderPostCancelPlugins(OrderTransfer $orderTransfer): OrderTransfer
    {
        foreach ($this->orderPostCancelPlugins as $orderPostCancelPlugin) {
            $orderTransfer = $orderPostCancelPlugin->postCancel($orderTransfer);
        }

        return $orderTransfer;
    }
}
