<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantPortalGui\Communication\Controller;

use Generated\Shared\Transfer\MerchantOrderCriteriaTransfer;
use Generated\Shared\Transfer\MerchantOrderTransfer;
use Generated\Shared\Transfer\OrderItemFilterTransfer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \Spryker\Zed\SalesMerchantPortalGui\Communication\SalesMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\SalesMerchantPortalGui\Persistence\SalesMerchantPortalGuiRepositoryInterface getRepository()
 */
class DetailController extends AbstractSalesMerchantPortalGuiController
{
    protected const PARAM_ID_MERCHANT_ORDER = 'merchant-order-id';

    /**
     * @uses \Spryker\Shared\Shipment\ShipmentConfig::SHIPMENT_EXPENSE_TYPE
     */
    protected const SHIPMENT_EXPENSE_TYPE = 'SHIPMENT_EXPENSE_TYPE';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function indexAction(Request $request): JsonResponse
    {
        $idMerchantOrder = $this->castId($request->get(static::PARAM_ID_MERCHANT_ORDER));
        $merchantOrderTransfer = $this->getFactory()
            ->getMerchantSalesOrderFacade()
            ->findMerchantOrder(
                (new MerchantOrderCriteriaTransfer())
                    ->setIdMerchantOrder($idMerchantOrder)
                    ->setWithOrder(true)
                    ->setWithItems(true)
                    ->setWithUniqueProductsCount(true)
            );

        if (!$merchantOrderTransfer || !$this->isMerchantOrderBelongsCurrentMerchant($merchantOrderTransfer)) {
            throw new NotFoundHttpException(sprintf('Merchant order is not found for id %d.', $idMerchantOrder));
        }

        $responseData = [
            'html' => $this->renderView('@SalesMerchantPortalGui/Partials/merchant_order_detail.twig', [
                'merchantOrder' => $merchantOrderTransfer,
                'customerMerchantOrderNumber' => $this->getCustomerMerchantOrderNumber($merchantOrderTransfer),
                'shipmentsNumber' => $this->getShipmentsNumber($merchantOrderTransfer),
                'merchantOrderItemTableConfiguration' => $this->getFactory()
                    ->createMerchantOrderItemGuiTableConfigurationProvider()
                    ->getConfiguration($merchantOrderTransfer),
                'merchantOrderItemsIndexedByShipment' => $this->getMerchantOrderItemTransfersIndexedByIdShipment($merchantOrderTransfer),
            ])->getContent(),
        ];

        return new JsonResponse($responseData);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function totalItemListAction(Request $request): Response
    {
        $idMerchantOrder = $this->castId($request->get(static::PARAM_ID_MERCHANT_ORDER));

        $merchantOrderTransfer = $this->getFactory()
            ->getMerchantSalesOrderFacade()
            ->findMerchantOrder(
                (new MerchantOrderCriteriaTransfer())
                    ->setIdMerchantOrder($idMerchantOrder)
                    ->setWithItems(true)
            );

        if (!$merchantOrderTransfer || !$this->isMerchantOrderBelongsCurrentMerchant($merchantOrderTransfer)) {
            throw new NotFoundHttpException(sprintf('Merchant order is not found for id %d.', $idMerchantOrder));
        }

        $salesOrderItemIds = $this->getSalesOrderItemIds($merchantOrderTransfer);
        $itemCollectionTransfer = $this->getFactory()->getSalesFacade()->getOrderItems(
            (new OrderItemFilterTransfer())->setSalesOrderItemIds($salesOrderItemIds)
        );

        return $this->renderView('@SalesMerchantPortalGui/Partials/order_items_list.twig', [
            'orderItems' => $itemCollectionTransfer->getItems(),
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     *
     * @return int
     */
    protected function getCustomerMerchantOrderNumber(MerchantOrderTransfer $merchantOrderTransfer): int
    {
        $customerMerchantOrderNumber = 0;

        /** @var \Generated\Shared\Transfer\OrderTransfer $orderTransfer */
        $orderTransfer = $merchantOrderTransfer->requireOrder()->getOrder();

        if (!$orderTransfer->getCustomerReference()) {
            return $customerMerchantOrderNumber;
        }

        $customerMerchantOrderNumber = $this->getFactory()
            ->getMerchantSalesOrderFacade()
            ->getMerchantOrdersCount(
                (new MerchantOrderCriteriaTransfer())
                    ->setCustomerReference($orderTransfer->getCustomerReference())
                    ->setMerchantReference($merchantOrderTransfer->getMerchantReference())
            );

        return $customerMerchantOrderNumber;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     *
     * @return int
     */
    protected function getShipmentsNumber(MerchantOrderTransfer $merchantOrderTransfer): int
    {
        $shipmentsNumber = 0;
        foreach ($merchantOrderTransfer->getExpenses() as $expenseTransfer) {
            if ($expenseTransfer->getType() !== static::SHIPMENT_EXPENSE_TYPE) {
                continue;
            }
            $shipmentsNumber++;
        }

        return $shipmentsNumber;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     *
     * @return int[]
     */
    protected function getSalesOrderItemIds(MerchantOrderTransfer $merchantOrderTransfer): array
    {
        $salesOrderItemIds = [];
        foreach ($merchantOrderTransfer->getMerchantOrderItems() as $merchantOrderItem) {
            /** @var int $idOrderItem */
            $idOrderItem = $merchantOrderItem->requireIdOrderItem()->getIdOrderItem();
            $salesOrderItemIds[] = $idOrderItem;
        }

        return $salesOrderItemIds;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderItemTransfer[][]
     */
    protected function getMerchantOrderItemTransfersIndexedByIdShipment(MerchantOrderTransfer $merchantOrderTransfer): array
    {
        $merchantOrderItemTransfers = [];
        foreach ($merchantOrderTransfer->getMerchantOrderItems() as $merchantOrderItemTransfer) {
            /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
            $itemTransfer = $merchantOrderItemTransfer->requireOrderItem()->getOrderItem();
            /** @var \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer */
            $shipmentTransfer = $itemTransfer->getShipment();
            /** @var int $idSalesShipment */
            $idSalesShipment = $shipmentTransfer->requireIdSalesShipment()->getIdSalesShipment();

            $merchantOrderItemTransfers[$idSalesShipment][] = $merchantOrderItemTransfer;
        }

        return $merchantOrderItemTransfers;
    }
}
