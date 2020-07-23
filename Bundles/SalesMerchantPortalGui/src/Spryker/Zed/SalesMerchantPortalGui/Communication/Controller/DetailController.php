<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantPortalGui\Communication\Controller;

use Generated\Shared\Transfer\ItemCollectionTransfer;
use Generated\Shared\Transfer\MerchantOrderCriteriaTransfer;
use Generated\Shared\Transfer\MerchantOrderTransfer;
use Generated\Shared\Transfer\OrderItemFilterTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \Spryker\Zed\SalesMerchantPortalGui\Communication\SalesMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\SalesMerchantPortalGui\Persistence\SalesMerchantPortalGuiRepositoryInterface getRepository()
 */
class DetailController extends AbstractController
{
    protected const PARAM_ID_MERCHANT_ORDER = 'id-merchant-order';

    /**
     * @uses \Spryker\Shared\Shipment\ShipmentConfig::SHIPMENT_EXPENSE_TYPE
     */
    protected const SHIPMENT_EXPENSE_TYPE = 'SHIPMENT_EXPENSE_TYPE';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
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

        $this->assureMerchantOrderExists($idMerchantOrder, $merchantOrderTransfer);

        $responseData = [
            'html' => $this->renderView('@SalesMerchantPortalGui/Partials/merchant_order_detail.twig', [
                'merchantOrder' => $merchantOrderTransfer,
                'customerMerchantOrderNumber' => $this->getCustomerMerchantOrderNumber($merchantOrderTransfer),
                'shipmentsNumber' => $this->getShipmentsNumber($merchantOrderTransfer),
            ])->getContent(),
        ];

        return new JsonResponse($responseData);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
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

        $this->assureMerchantOrderExists($idMerchantOrder, $merchantOrderTransfer);

        $saleOrderItemIds = $this->getSaleOrderItemIds($merchantOrderTransfer);
        $itemCollectionTransfer = $this->getFactory()->getSalesFacade()->getOrderItems(
            (new OrderItemFilterTransfer())->setSalesOrderItemIds($saleOrderItemIds)
        );

        return $this->renderView('@SalesMerchantPortalGui/Partials/order_items_list.twig', [
            'orderItems' => $this->getSalesOrderItemsGroupedBySku($itemCollectionTransfer),
        ]);
    }

    /**
     * @param int $idMerchantOrder
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer|null $merchantOrderTransfer
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return void
     */
    protected function assureMerchantOrderExists(int $idMerchantOrder, ?MerchantOrderTransfer $merchantOrderTransfer): void
    {
        if (!$merchantOrderTransfer) {
            throw new NotFoundHttpException(sprintf('Merchant order not found for id %d.', $idMerchantOrder));
        }

        $currentMerchantUserTransfer = $this->getFactory()->getMerchantUserFacade()->getCurrentMerchantUser();
        if ($currentMerchantUserTransfer->getMerchant()->getMerchantReference() !== $merchantOrderTransfer->getMerchantReference()) {
            throw new NotFoundHttpException(sprintf('Merchant order not found for id %d.', $idMerchantOrder));
        }
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     *
     * @return int
     */
    protected function getCustomerMerchantOrderNumber(MerchantOrderTransfer $merchantOrderTransfer): int
    {
        $merchantOrderTransfer->requireOrder();

        $customerMerchantOrderNumber = 0;

        if (!$merchantOrderTransfer->getOrder()->getCustomerReference()) {
            return $customerMerchantOrderNumber;
        }

        return $this->getFactory()
            ->getMerchantSalesOrderFacade()
            ->getMerchantOrdersCount(
                (new MerchantOrderCriteriaTransfer())
                    ->setCustomerReference($merchantOrderTransfer->getOrder()->getCustomerReference())
                    ->setMerchantReference($merchantOrderTransfer->getMerchantReference())
            );
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
    protected function getSaleOrderItemIds(MerchantOrderTransfer $merchantOrderTransfer): array
    {
        $saleOrderItemIds = [];
        foreach ($merchantOrderTransfer->getMerchantOrderItems() as $merchantOrderItem) {
            $saleOrderItemIds[] = $merchantOrderItem->getIdOrderItem();
        }

        return $saleOrderItemIds;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemCollectionTransfer $itemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function getSalesOrderItemsGroupedBySku(ItemCollectionTransfer $itemCollectionTransfer): array
    {
        /** @var \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers */
        $itemTransfers = [];
        foreach ($itemCollectionTransfer->getItems() as $itemTransfer) {
            if (!isset($itemTransfers[$itemTransfer->getSku()])) {
                $itemTransfers[$itemTransfer->getSku()] = $itemTransfer;

                continue;
            }

            $itemTransfers[$itemTransfer->getSku()]->setQuantity(
                $itemTransfers[$itemTransfer->getSku()]->getQuantity() + $itemTransfer->getQuantity()
            );
        }

        return $itemTransfers;
    }
}
