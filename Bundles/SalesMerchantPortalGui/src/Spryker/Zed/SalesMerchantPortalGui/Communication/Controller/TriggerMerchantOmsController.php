<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantPortalGui\Communication\Controller;

use ArrayObject;
use Generated\Shared\Transfer\MerchantOmsTriggerRequestTransfer;
use Generated\Shared\Transfer\MerchantOrderCriteriaTransfer;
use Generated\Shared\Transfer\MerchantOrderItemCollectionTransfer;
use Generated\Shared\Transfer\MerchantOrderItemCriteriaTransfer;
use Spryker\Zed\Kernel\Exception\Controller\InvalidIdException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\SalesMerchantPortalGui\Communication\SalesMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\SalesMerchantPortalGui\Persistence\SalesMerchantPortalGuiRepositoryInterface getRepository()
 */
class TriggerMerchantOmsController extends AbstractSalesMerchantPortalGuiController
{
    protected const PARAM_ID_MERCHANT_ORDER = 'merchant-order-id';
    protected const PARAM_MERCHANT_ORDER_IDS = 'merchant-order-ids';
    protected const PARAM_EVENT_NAME = 'event-name';

    protected const MESSAGE_STATUS_CHANGED_SUCCESSFULLY = 'The state is updated successfully.';

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

        if (!$merchantOrderTransfer || !$this->isMerchantOrderBelongsCurrentMerchant($merchantOrderTransfer)) {
            return $this->getErrorResponse(sprintf('Merchant order is not found for id %d.', $idMerchantOrder));
        }

        $this->triggerEventFormMerchantOrderItems($eventName, $merchantOrderTransfer->getMerchantOrderItems());

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
                    'message' => $this->getFactory()
                        ->getTranslatorFacade()
                        ->trans(static::MESSAGE_STATUS_CHANGED_SUCCESSFULLY),
                ],
            ],
        ];

        return new JsonResponse($responseData);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function batchAction(Request $request): JsonResponse
    {
        $eventName = $request->get(static::PARAM_EVENT_NAME);
        if (!$eventName) {
            return $this->getErrorResponse('Event name is empty.');
        }

        try {
            $idMerchantOrder = $this->castId($request->get(static::PARAM_ID_MERCHANT_ORDER));
        } catch (InvalidIdException $exception) {
            return $this->getErrorResponse($exception->getMessage());
        }

        $merchantOrderIds = $request->get(static::PARAM_MERCHANT_ORDER_IDS);
        if (!$merchantOrderIds) {
            return $this->getErrorResponse('Merchant order ids are empty.');
        }

        $merchantOrderIds = array_map(function ($value) {
            return (int)$value;
        }, explode(',', trim($merchantOrderIds, '[]')));

        $merchantOrderTransfer = $this->getFactory()
            ->getMerchantSalesOrderFacade()
            ->findMerchantOrder(
                (new MerchantOrderCriteriaTransfer())
                    ->setIdMerchantOrder($idMerchantOrder)
            );

        if (!$merchantOrderTransfer || !$this->isMerchantOrderBelongsCurrentMerchant($merchantOrderTransfer)) {
            return $this->getErrorResponse(sprintf('Merchant order is not found for id %d.', $idMerchantOrder));
        }

        $merchantOrderItemCollectionTransfer = $this->getFactory()
            ->getMerchantSalesOrderFacade()
            ->getMerchantOrderItemCollection(
                (new MerchantOrderItemCriteriaTransfer())
                    ->setMerchantOrderItemIds($merchantOrderIds)
            );

        $this->triggerEventFormMerchantOrderItems($eventName, $this->filterMerchantOrderItems($merchantOrderItemCollectionTransfer, $idMerchantOrder));

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
     * @phpstan-param \ArrayObject<int,\Generated\Shared\Transfer\MerchantOrderItemTransfer> $merchantOrderItemTransfers
     *
     * @param string $eventName
     * @param \Generated\Shared\Transfer\MerchantOrderItemTransfer[]|\ArrayObject $merchantOrderItemTransfers
     *
     * @return int
     */
    protected function triggerEventFormMerchantOrderItems(string $eventName, ArrayObject $merchantOrderItemTransfers): int
    {
        $merchantOmsTriggerRequestTransfer = (new MerchantOmsTriggerRequestTransfer())
            ->setMerchantOmsEventName($eventName)
            ->setMerchantOrderItems($merchantOrderItemTransfers);

        return $this->getFactory()
            ->getMerchantOmsFacade()
            ->triggerEventForMerchantOrderItems($merchantOmsTriggerRequestTransfer);
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
                    'message' => $this->getFactory()
                        ->getTranslatorFacade()
                        ->trans($errorMessage),
                ],
            ],
        ]);
    }

    /**
     * @phpstan-return \ArrayObject<int,\Generated\Shared\Transfer\MerchantOrderItemTransfer>
     *
     * @param \Generated\Shared\Transfer\MerchantOrderItemCollectionTransfer $merchantOrderItemCollectionTransfer
     * @param int $idMerchantOrder
     *
     * @return \Generated\Shared\Transfer\MerchantOrderItemTransfer[]|\ArrayObject
     */
    protected function filterMerchantOrderItems(MerchantOrderItemCollectionTransfer $merchantOrderItemCollectionTransfer, int $idMerchantOrder): ArrayObject
    {
        $merchantOrderItems = new ArrayObject();
        foreach ($merchantOrderItemCollectionTransfer->getMerchantOrderItems() as $merchantOrderItemTransfer) {
            if ($merchantOrderItemTransfer->getIdMerchantOrder() !== $idMerchantOrder) {
                continue;
            }

            $merchantOrderItems->append($merchantOrderItemTransfer);
        }

        return $merchantOrderItems;
    }
}
