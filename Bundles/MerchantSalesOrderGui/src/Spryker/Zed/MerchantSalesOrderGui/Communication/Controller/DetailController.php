<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderGui\Communication\Controller;

use ArrayObject;
use Generated\Shared\Transfer\MerchantOrderCriteriaTransfer;
use Generated\Shared\Transfer\MerchantOrderItemCollectionTransfer;
use Generated\Shared\Transfer\MerchantOrderTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\MerchantSalesOrderGui\MerchantSalesOrderGuiConfig;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * @method \Spryker\Zed\MerchantSalesOrderGui\Communication\MerchantSalesOrderGuiCommunicationFactory getFactory()
 */
class DetailController extends AbstractController
{
    protected const PARAM_ID_MERCHANT_SALES_ORDER = 'id-merchant-sales-order';

    public const ROUTE_REDIRECT = '/merchant-sales-order-gui/detail';

    /**
     * @uses \Spryker\Zed\Http\Communication\Plugin\Application\HttpApplicationPlugin::SERVICE_SUB_REQUEST
     */
    protected const SERVICE_SUB_REQUEST = 'sub_request';

    protected const MASSAGE_MERCHANT_ORDER_EXIST = 'Merchant order doesn\'t exist.';

    /**
     * @phpstan-return array<string, mixed>
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
     *
     * @return array
     */
    public function indexAction(Request $request): array
    {
        $idMerchantSalesOrder = $this->castId($request->query->getInt(MerchantSalesOrderGuiConfig::REQUEST_ID_MERCHANT_SALES_ORDER));
        $idMerchant = $this->getFactory()->getMerchantUserFacade()->getCurrentMerchantUser()->getIdMerchant();

        $merchantOrderTransfer = $this->getFactory()->getMerchantSalesOrderFacade()->findMerchantOrder(
            (new MerchantOrderCriteriaTransfer())
                ->setIdMerchantOrder($idMerchantSalesOrder)
                ->setIdMerchant($idMerchant)
                ->setWithItems(true)
                ->setWithOrder(true)
        );
        if (!$merchantOrderTransfer) {
            throw new AccessDeniedHttpException(static::MASSAGE_MERCHANT_ORDER_EXIST);
        }

        $merchantOrderTransfer = $this->getFactory()
            ->getMerchantOmsFacade()
            ->expandMerchantOrderItemsWithStateHistory($merchantOrderTransfer);

        $merchantOrderTransfer = $this->getFactory()
            ->getMerchantOmsFacade()
            ->expandMerchantOrderWithMerchantOmsData($merchantOrderTransfer);

        $merchantOrderItemCollectionTransfer = $this->getFactory()
            ->getMerchantOmsFacade()
            ->expandMerchantOrderItemsWithManualEvents(
                (new MerchantOrderItemCollectionTransfer())
                ->setMerchantOrderItems($merchantOrderTransfer->getMerchantOrderItems())
            );
        $merchantOrderTransfer->setMerchantOrderItems($merchantOrderItemCollectionTransfer->getMerchantOrderItems());

        $blockData = $this->renderMultipleActions(
            $request,
            $this->getFactory()->getMerchantSalesOrderDetailExternalBlocksUrls(),
            $merchantOrderTransfer
        );
        $groupedMerchantOrderItemsByShipment = $this->getFactory()->getShipmentService()->groupItemsByShipment($merchantOrderTransfer->getOrder()->getItems());

        $merchantOrderItemsWithOrderItemIdKey = [];
        foreach ($merchantOrderTransfer->getMerchantOrderItems() as $merchantOrderItem) {
            $merchantOrderItemsWithOrderItemIdKey[$merchantOrderItem->getOrderItem()->getIdSalesOrderItem()] = $merchantOrderItem;
        }

        $uniqueEventsGroupedByShipmentId = $this->extractUniqueEvents($groupedMerchantOrderItemsByShipment, $merchantOrderItemsWithOrderItemIdKey);

        return [
            'merchantOrder' => $merchantOrderTransfer,
            'groupedMerchantOrderItemsByShipment' => $groupedMerchantOrderItemsByShipment,
            'totalMerchantOrderCount' => $this->getFactory()->getMerchantSalesOrderFacade()->getMerchantOrdersCount(
                (new MerchantOrderCriteriaTransfer())->setMerchantReference($merchantOrderTransfer->getMerchantReference())
            ),
            'changeStatusRedirectUrl' => $this->createRedirectLink($idMerchantSalesOrder),
            'merchantOrderItemsWithOrderItemIdKey' => $merchantOrderItemsWithOrderItemIdKey,
            'uniqueEventsGroupedByShipmentId' => $uniqueEventsGroupedByShipmentId,
            'blocks' => $blockData,
        ];
    }

    /**
     * @phpstan-param \ArrayObject<int, \Generated\Shared\Transfer\ShipmentGroupTransfer> $groupedMerchantOrderItemsByShipment
     * @phpstan-param array<int, \Generated\Shared\Transfer\MerchantOrderItemTransfer> $merchantOrderItemsWithOrderItemIdKey
     *
     * @phpstan-return array<int|string, array<int, string>>
     *
     * @param \ArrayObject|\Generated\Shared\Transfer\ShipmentGroupTransfer[] $groupedMerchantOrderItemsByShipment
     * @param array $merchantOrderItemsWithOrderItemIdKey
     *
     * @return array
     */
    protected function extractUniqueEvents(ArrayObject $groupedMerchantOrderItemsByShipment, array $merchantOrderItemsWithOrderItemIdKey): array
    {
        $events = [];

        foreach ($groupedMerchantOrderItemsByShipment as $shipmentGroupTransfer) {
            $eventsForGroup = [];
            foreach ($shipmentGroupTransfer->getItems() as $itemTransfer) {
                $merchantOrderItemTransfer = $merchantOrderItemsWithOrderItemIdKey[$itemTransfer->getIdSalesOrderItem()];
                $eventsForGroup = array_merge($eventsForGroup, $merchantOrderItemTransfer->getManualEvents());
            }
            $events[$shipmentGroupTransfer->getShipment()->getIdSalesShipment()] = $eventsForGroup;
        }

        return array_unique($events);
    }

    /**
     * @param int $idMerchantSalesOrder
     *
     * @return string
     */
    protected function createRedirectLink(int $idMerchantSalesOrder): string
    {
        $redirectUrlParams = [
            static::PARAM_ID_MERCHANT_SALES_ORDER => $idMerchantSalesOrder,
        ];

        return Url::generate(static::ROUTE_REDIRECT, $redirectUrlParams);
    }

    /**
     * @phpstan-param array <string, string> $data
     *
     * @phpstan-return array <string, string>
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param array $data
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     *
     * @return array
     */
    protected function renderMultipleActions(Request $request, array $data, MerchantOrderTransfer $merchantOrderTransfer): array
    {
        $subRequest = clone $request;
        $subRequest->setMethod(Request::METHOD_POST);

        /** @var array $merchantOrderTransfer */
        $subRequest->request->set('merchantOrderTransfer', $merchantOrderTransfer);

        $responseData = [];
        foreach ($data as $blockName => $blockUrl) {
            $responseData[$blockName] = $this->handleSubRequest($subRequest, $blockUrl);
        }

        return $responseData;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $blockUrl
     *
     * @return string|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function handleSubRequest(Request $request, string $blockUrl)
    {
        $blockResponse = $this->getApplication()->get(static::SERVICE_SUB_REQUEST)->handleSubRequest($request, $blockUrl);
        if ($blockResponse instanceof RedirectResponse) {
            return $blockResponse;
        }

        return $blockResponse->getContent();
    }
}
