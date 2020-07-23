<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantPortalGui\Communication\Controller;

use Generated\Shared\Transfer\MerchantOmsTriggerRequestTransfer;
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
class TriggerMerchantOmsController extends AbstractController
{
    protected const PARAM_ID_MERCHANT_ORDER = 'id-merchant-order';
    protected const PARAM_EVENT_NAME = 'event-name';

    protected const MESSAGE_STATUS_CHANGED_SUCCESSFULLY = 'Status change triggered successfully.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function indexAction(Request $request): JsonResponse
    {
        $idMerchantOrder = $this->castId($request->get(static::PARAM_ID_MERCHANT_ORDER));
        $eventName = $request->get(static::PARAM_EVENT_NAME);

        $merchantOrderTransfer = $this->getFactory()
            ->getMerchantSalesOrderFacade()
            ->findMerchantOrder(
                (new MerchantOrderCriteriaTransfer())
                    ->setIdMerchantOrder($idMerchantOrder)
                    ->setWithItems(true)
            );

        $this->assureMerchantOrderExists($idMerchantOrder, $merchantOrderTransfer);

        $merchantOmsTriggerRequestTransfer = (new MerchantOmsTriggerRequestTransfer())
            ->setMerchantOmsEventName($eventName);

        foreach ($merchantOrderTransfer->getMerchantOrderItems() as $merchantOrderItemTransfer) {
            $merchantOmsTriggerRequestTransfer->addMerchantOrderItem($merchantOrderItemTransfer);
        }

        $this->getFactory()
            ->getMerchantOmsFacade()
            ->triggerEventForMerchantOrderItems($merchantOmsTriggerRequestTransfer);

        $postActionUrl = sprintf('/sales-merchant-portal-gui/detail?%s=%d', static::PARAM_ID_MERCHANT_ORDER, $idMerchantOrder);

        $responseData = [
            'postActions' => [
                [
                    'type' => 'refresh_overlay',
                    'url' => $postActionUrl,
                ],
            ],
            'notifications' => [
                [
                    'type' => 'success',
                    'message' => static::MESSAGE_STATUS_CHANGED_SUCCESSFULLY,
                ],
            ],
        ];

        return new JsonResponse($responseData);
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
}
