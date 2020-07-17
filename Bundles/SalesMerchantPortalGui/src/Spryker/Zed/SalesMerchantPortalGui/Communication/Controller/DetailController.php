<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantPortalGui\Communication\Controller;

use Generated\Shared\Transfer\MerchantOrderCriteriaTransfer;
use Generated\Shared\Transfer\MerchantOrderTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \Spryker\Zed\SalesMerchantPortalGui\Communication\SalesMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\SalesMerchantPortalGui\Persistence\SalesMerchantPortalGuiRepositoryInterface getRepository()
 */
class DetailController extends AbstractController
{
    protected const PARAM_ID_MERCHANT_ORDER = 'id-merchant-order';
    protected const MESSAGE_ERROR_MERCHANT_ORDER_NOT_FOUND = 'Merchant order not found for id %d.';

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
                    ->setWithUniqueProductCount(true)
            );

        if (!$merchantOrderTransfer) {
            throw new NotFoundHttpException(sprintf(static::MESSAGE_ERROR_MERCHANT_ORDER_NOT_FOUND, $idMerchantOrder));
        }

        $currentMerchantUserTransfer = $this->getFactory()->getMerchantUserFacade()->getCurrentMerchantUser();
        if ($currentMerchantUserTransfer->getMerchant()->getMerchantReference() !== $merchantOrderTransfer->getMerchantReference()) {
            throw new NotFoundHttpException(sprintf(static::MESSAGE_ERROR_MERCHANT_ORDER_NOT_FOUND, $idMerchantOrder));
        }

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
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     *
     * @return int
     */
    protected function getCustomerMerchantOrderNumber(MerchantOrderTransfer $merchantOrderTransfer): int
    {
        $customerMerchantOrderNumber = 0;

        if (!$merchantOrderTransfer->getOrder() || !$merchantOrderTransfer->getOrder()->getCustomerReference()) {
            return $customerMerchantOrderNumber;
        }

        $merchantOrderCollectionTransfer = $this->getFactory()
            ->getMerchantSalesOrderFacade()
            ->getMerchantOrderCollection(
                (new MerchantOrderCriteriaTransfer())
                    ->setCustomerReference($merchantOrderTransfer->getOrder()->getCustomerReference())
                    ->setMerchantReference($merchantOrderTransfer->getMerchantReference())
            );

        return $merchantOrderCollectionTransfer->getMerchantOrders()->count();
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
}
