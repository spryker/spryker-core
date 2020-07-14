<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantPortalGui\Communication\Controller;

use Generated\Shared\Transfer\MerchantOrderCriteriaTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\SalesMerchantPortalGui\Communication\SalesMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\SalesMerchantPortalGui\Persistence\SalesMerchantPortalGuiRepositoryInterface getRepository()
 */
class DetailController extends AbstractController
{
    protected const PARAM_ID_MERCHANT_ORDER = 'id-merchant-order';

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
                    ->setWithItems(true)
                    ->setWithOrder(true)
                    ->setWithUniqueProductCount(true)
            );

        $customerMerchantOrderNumber = 0;

        if (
            $merchantOrderTransfer
            && $merchantOrderTransfer->getOrder()
            && $merchantOrderTransfer->getOrder()->getCustomerReference()
        ) {
            $merchantOrderCollectionTransfer = $this->getFactory()
                ->getMerchantSalesOrderFacade()
                ->getMerchantOrderCollection(
                    (new MerchantOrderCriteriaTransfer())
                        ->setCustomerReference($merchantOrderTransfer->getOrder()->getCustomerReference())
                        ->setMerchantReference($merchantOrderTransfer->getMerchantReference())
                );

            $customerMerchantOrderNumber = $merchantOrderCollectionTransfer->getMerchantOrders()->count();
        }

        $responseData = [
            'html' => $this->renderView('@SalesMerchantPortalGui/Partials/merchant_order_detail.twig', [
                'merchantOrder' => $merchantOrderTransfer,
                'customerMerchantOrderNumber' => $customerMerchantOrderNumber,
            ])->getContent(),
        ];

        return new JsonResponse($responseData);
    }
}
