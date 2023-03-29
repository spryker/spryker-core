<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\OrderStatusChanged;

use ArrayObject;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\MessageAttributesTransfer;
use Generated\Shared\Transfer\OrderItemTransfer;
use Generated\Shared\Transfer\OrderStatusChangedTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Zed\Oms\Dependency\Facade\OmsToMessageBrokerInterface;
use Spryker\Zed\Oms\Dependency\Facade\OmsToSalesInterface;
use Spryker\Zed\Oms\Dependency\Facade\OmsToStoreFacadeInterface;
use Spryker\Zed\Oms\OmsConfig;
use Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface;

class OrderStatusChangedMessageSender implements OrderStatusChangedMessageSenderInterface
{
    /**
     * @var \Spryker\Zed\Oms\Dependency\Facade\OmsToMessageBrokerInterface
     */
    protected $messageBrokerFacade;

    /**
     * @var \Spryker\Zed\Oms\Dependency\Facade\OmsToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\Oms\Dependency\Facade\OmsToSalesInterface
     */
    protected $salesFacade;

    /**
     * @var \Spryker\Zed\Oms\OmsConfig
     */
    protected $omsConfig;

    /**
     * @var \Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\Oms\Dependency\Facade\OmsToMessageBrokerInterface $messageBrokerFacade
     * @param \Spryker\Zed\Oms\Dependency\Facade\OmsToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\Oms\Dependency\Facade\OmsToSalesInterface $salesFacade
     * @param \Spryker\Zed\Oms\OmsConfig $omsConfig
     * @param \Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface $queryContainer
     */
    public function __construct(
        OmsToMessageBrokerInterface $messageBrokerFacade,
        OmsToStoreFacadeInterface $storeFacade,
        OmsToSalesInterface $salesFacade,
        OmsConfig $omsConfig,
        OmsQueryContainerInterface $queryContainer
    ) {
        $this->messageBrokerFacade = $messageBrokerFacade;
        $this->storeFacade = $storeFacade;
        $this->salesFacade = $salesFacade;
        $this->omsConfig = $omsConfig;
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param int $idSalesOrder
     *
     * @return void
     */
    public function sendMessage(int $idSalesOrder): void
    {
        $orderStatusChangedTransfer = $this->createOrderStatusChangedTransfer($idSalesOrder);
        $this->setMessageAttributesTransfer($orderStatusChangedTransfer);

        $this->messageBrokerFacade->sendMessage($orderStatusChangedTransfer);
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\OrderStatusChangedTransfer
     */
    protected function createOrderStatusChangedTransfer(int $idSalesOrder): OrderStatusChangedTransfer
    {
        $orderTransfer = $this->salesFacade->getOrderByIdSalesOrder($idSalesOrder);
        $this->expandOrderTransferWithLocale($orderTransfer, $idSalesOrder);

        $filteredDataFromOrder = $this->mapOrderDataByAllowedFields($orderTransfer, $this->omsConfig->getOrderFieldsForOrderStatusChangedMessage());

        $orderStatusChangedTransfer = new OrderStatusChangedTransfer();
        $orderStatusChangedTransfer->fromArray($filteredDataFromOrder);
        $orderStatusChangedTransfer->setUserName($orderTransfer->getFirstName() . ' ' . $orderTransfer->getLastName());

        $states = $orderTransfer->getItemStates();
        $state = !$states ? null : end($states);
        $orderStatusChangedTransfer->setStatus($state);

        $processedListOfSkus = [];
        foreach ($orderTransfer->getItems() as $orderItem) {
            if (in_array($orderItem->getSku(), $processedListOfSkus)) {
                continue;
            }
            $orderItemTransfer = new OrderItemTransfer();
            $orderItemTransfer->setProductId($orderItem->getSku());
            $orderItemTransfer->setName($orderItem->getName());
            $orderItemTransfer->setImageUrl($orderItem->getMetadata()->getImage());
            $orderItemTransfer->setPrice($orderItem->getUnitPrice());
            $orderStatusChangedTransfer->addOrderItem($orderItemTransfer);
            $processedListOfSkus[] = $orderItem->getSku();
        }

        if (method_exists(OrderTransfer::class, 'getMerchants')) {
            $orderStatusChangedTransfer->setMerchants($orderTransfer->getMerchants());
        }

        return $orderStatusChangedTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderStatusChangedTransfer $orderStatusChangedTransfer
     *
     * @return void
     */
    protected function setMessageAttributesTransfer(OrderStatusChangedTransfer $orderStatusChangedTransfer): void
    {
        $messageAttributes = new MessageAttributesTransfer();
        $messageAttributes->setStoreReference($this->storeFacade->getCurrentStore()->getStoreReference());

        $orderStatusChangedTransfer->setMessageAttributes($messageAttributes);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param array $orderFieldsAllowedForSending
     *
     * @return array
     */
    protected function mapOrderDataByAllowedFields(OrderTransfer $orderTransfer, array $orderFieldsAllowedForSending): array
    {
        return $this->mapTransferDataByAllowedFieldsRecursive(
            $orderTransfer,
            $orderFieldsAllowedForSending,
            [],
        );
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $transfer
     * @param array $allowedFields
     * @param array $mappedData
     *
     * @return array
     */
    protected function mapTransferDataByAllowedFieldsRecursive(
        AbstractTransfer $transfer,
        array $allowedFields,
        array $mappedData
    ): array {
        $camelCasedData = $transfer->toArray(false, true);

        foreach ($allowedFields as $fieldName => $allowedData) {
            $fieldValue = $camelCasedData[$fieldName];

            if ($fieldValue == null) {
                continue;
            }

            if (is_array($allowedData) && $fieldValue instanceof AbstractTransfer) {
                $mappedData = $this->mapTransferDataByAllowedFieldsRecursive($fieldValue, $allowedData, $mappedData);

                continue;
            }

            if (is_array($allowedData) && $fieldValue instanceof ArrayObject) {
                foreach ($fieldValue as $transfer) {
                    $mappedData[$fieldName][] = $this->mapTransferDataByAllowedFieldsRecursive($transfer, $allowedData, []);
                }

                continue;
            }

            $mappedData[$allowedData] = $fieldValue;
        }

        return $mappedData;
    }

    /**
     * @deprecated This is just for backward compatibility for projects using Sales module lower than version 8.14.1.
     * Will be replaced by SalesFacade::findOrderByIdSalesOrder which already takes care of locale when all projects require spryker/sales:v8.14.1 as minimum.
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function expandOrderTransferWithLocale(OrderTransfer $orderTransfer, int $idSalesOrder): OrderTransfer
    {
        if ($orderTransfer->getLocale()) {
            return $orderTransfer;
        }

        $orderEntity = $this->queryContainer->querySalesOrderById($idSalesOrder)->findOne();

        if ($orderEntity->getLocale()) {
            $localeTransfer = (new LocaleTransfer())->fromArray($orderEntity->getLocale()->toArray(), true);
            $orderTransfer->setLocale($localeTransfer);
        }

        return $orderTransfer;
    }
}
