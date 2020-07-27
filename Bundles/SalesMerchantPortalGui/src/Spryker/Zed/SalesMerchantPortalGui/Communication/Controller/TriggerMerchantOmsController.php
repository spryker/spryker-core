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

        if (!$eventName) {
            return $this->getErrorResponse('Event name is empty.');
        }

        $merchantOrderTransfer = $this->getFactory()
            ->getMerchantSalesOrderFacade()
            ->findMerchantOrder(
                (new MerchantOrderCriteriaTransfer())
                    ->setIdMerchantOrder($idMerchantOrder)
                    ->setWithItems(true)
            );

        if (!$this->isMerchantOrderExists($idMerchantOrder, $merchantOrderTransfer)) {
            return $this->getErrorResponse(sprintf('Merchant order not found for id %d.', $idMerchantOrder));
        }

        $merchantOmsTriggerRequestTransfer = (new MerchantOmsTriggerRequestTransfer())
            ->setMerchantOmsEventName($eventName);

        $merchantOmsTriggerRequestTransfer->setMerchantOrderItems(
            $merchantOrderTransfer->getMerchantOrderItems()
        );

        $this->getFactory()
            ->getMerchantOmsFacade()
            ->triggerEventForMerchantOrderItems($merchantOmsTriggerRequestTransfer);

        $responseData = [
            'postActions' => [
                [
                    'type' => 'refresh_drawer',
                ],
                [
                    'type' => 'refresh_table',
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
     * @return bool
     */
    protected function isMerchantOrderExists(int $idMerchantOrder, ?MerchantOrderTransfer $merchantOrderTransfer): bool
    {
        if (!$merchantOrderTransfer) {
            return false;
        }

        $currentMerchantUserTransfer = $this->getFactory()->getMerchantUserFacade()->getCurrentMerchantUser();
        if ($currentMerchantUserTransfer->getMerchant()->getMerchantReference() !== $merchantOrderTransfer->getMerchantReference()) {
            return false;
        }

        return true;
    }

    /**
     * @param string $errorMessage
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function getErrorResponse(string $errorMessage): JsonResponse
    {
        return new JsonResponse([
            'notifications' => [
                [
                    'type' => 'error',
                    'message' => $errorMessage,
                ],
            ],
        ]);
    }
}
