<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantPortalGui\Communication\Controller;

use Generated\Shared\Transfer\MerchantOrderCriteriaTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \Spryker\Zed\SalesMerchantPortalGui\Communication\SalesMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\SalesMerchantPortalGui\Persistence\SalesMerchantPortalGuiRepositoryInterface getRepository()
 */
class ItemListController extends AbstractController
{
    protected const PARAM_MERCHANT_ORDER_ITEM_IDS = 'merchant-order-item-ids';
    protected const PARAM_MERCHANT_ORDER_ID = 'merchant-order-id';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function tableDataAction(Request $request): Response
    {
        $merchantOrderItemIds = $this->getMerchantOrderItemIds($request);
        $idMerchantOrder = $this->castId($request->get(static::PARAM_MERCHANT_ORDER_ID));

        $merchantOrderTransfer = $this->getFactory()
            ->getMerchantSalesOrderFacade()
            ->findMerchantOrder(
                (new MerchantOrderCriteriaTransfer())
                    ->setIdMerchantOrder($idMerchantOrder)
                    ->setWithItems(true)
            );

        if (!$merchantOrderTransfer) {
            throw new NotFoundHttpException(sprintf('Merchant order not found for id %d.', $idMerchantOrder));
        }

        return $this->getFactory()->getGuiTableHttpDataRequestExecutor()->execute(
            $request,
            $this->getFactory()->createMerchantOrderItemGuiTableDataProvider($merchantOrderItemIds),
            $this->getFactory()->createMerchantOrderItemGuiTableConfigurationProvider()->getConfiguration($merchantOrderTransfer)
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return int[]
     */
    protected function getMerchantOrderItemIds(Request $request): array
    {
        $merchantOrderItemIds = $request->get(static::PARAM_MERCHANT_ORDER_ITEM_IDS);
        if (!$merchantOrderItemIds) {
            return [];
        }

        return array_map(function ($value) {
            return (int)$value;
        }, explode(',', $merchantOrderItemIds));
    }
}
