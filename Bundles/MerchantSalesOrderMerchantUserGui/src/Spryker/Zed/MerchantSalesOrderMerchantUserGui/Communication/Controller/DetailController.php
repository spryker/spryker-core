<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderMerchantUserGui\Communication\Controller;

use ArrayObject;
use Generated\Shared\Transfer\MerchantOrderCriteriaTransfer;
use Generated\Shared\Transfer\MerchantOrderItemCollectionTransfer;
use Generated\Shared\Transfer\MerchantOrderTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\MerchantSalesOrderMerchantUserGui\MerchantSalesOrderMerchantUserGuiConfig;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\MerchantSalesOrderMerchantUserGui\Communication\MerchantSalesOrderMerchantUserGuiCommunicationFactory getFactory()
 */
class DetailController extends AbstractController
{
    protected const PARAM_ID_MERCHANT_SALES_ORDER = 'id-merchant-sales-order';

    protected const ROUTE_REDIRECT = '/merchant-sales-order-merchant-user-gui/detail';

    /**
     * @uses \Spryker\Zed\Http\Communication\Plugin\Application\HttpApplicationPlugin::SERVICE_SUB_REQUEST
     */
    protected const SERVICE_SUB_REQUEST = 'sub_request';

    protected const MESSAGE_ORDER_NOT_FOUND_ERROR = 'Merchant sales order #%d not found.';

    /**
     * @phpstan-return array<mixed>|\Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idMerchantSalesOrder = $this->castId(
            $request->query->getInt(MerchantSalesOrderMerchantUserGuiConfig::REQUEST_PARAM_ID_MERCHANT_SALES_ORDER)
        );
        $idMerchant = $this->getFactory()->getMerchantUserFacade()->getCurrentMerchantUser()->getIdMerchant();

        $merchantOrderTransfer = $this->findMerchantSalesOrder($idMerchantSalesOrder, $idMerchant);

        if (!$merchantOrderTransfer) {
            $this->addErrorMessage(static::MESSAGE_ORDER_NOT_FOUND_ERROR, ['%d' => $idMerchantSalesOrder]);
            $redirectUrl = Url::generate(static::ROUTE_REDIRECT)->build();

            return $this->redirectResponse($redirectUrl);
        }

        $merchantOrderItemCollectionTransfer = $this->getFactory()
            ->getMerchantOmsFacade()
            ->expandMerchantOrderItemsWithManualEvents(
                (new MerchantOrderItemCollectionTransfer())
                ->setMerchantOrderItems($merchantOrderTransfer->getMerchantOrderItems())
            );
        $merchantOrderTransfer->setMerchantOrderItems($merchantOrderItemCollectionTransfer->getMerchantOrderItems());

        $blockData = $this->renderActions(
            $request,
            $this->getFactory()->getMerchantSalesOrderDetailExternalBlocksUrls(),
            $merchantOrderTransfer
        );
        $groupedMerchantOrderItemsByShipment = $this->getFactory()->getShipmentService()->groupItemsByShipment(
            $merchantOrderTransfer->getOrder()->getItems()
        );

        $groupedMerchantOrderItems = $this->groupMerchantOrderItemsByIdSalesOrderItem($merchantOrderTransfer);

        return [
            'merchantOrder' => $merchantOrderTransfer,
            'groupedMerchantOrderItemsByShipment' => $groupedMerchantOrderItemsByShipment,
            'totalMerchantOrderCount' => $this->getFactory()->getMerchantSalesOrderFacade()->getMerchantOrdersCount(
                (new MerchantOrderCriteriaTransfer())->setMerchantReference($merchantOrderTransfer->getMerchantReference())
            ),
            'changeStatusRedirectUrl' => $this->createRedirectLink($idMerchantSalesOrder),
            'groupedMerchantOrderItems' => $groupedMerchantOrderItems,
            'uniqueEventsGroupedByShipmentId' => $this->extractUniqueEvents($groupedMerchantOrderItemsByShipment, $groupedMerchantOrderItems),
            'blocks' => $blockData,
        ];
    }

    /**
     * @param int $idMerchantSalesOrder
     * @param int $idMerchant
     *
     * @return \Generated\Shared\Transfer\MerchantOrderTransfer|null
     */
    protected function findMerchantSalesOrder(int $idMerchantSalesOrder, int $idMerchant): ?MerchantOrderTransfer
    {
        $merchantOrderCriteriaTransfer = (new MerchantOrderCriteriaTransfer())
            ->setIdMerchantOrder($idMerchantSalesOrder)
            ->setIdMerchant($idMerchant)
            ->setWithItems(true)
            ->setWithOrder(true);

        $merchantOrderTransfer = $this->getFactory()
            ->getMerchantSalesOrderFacade()
            ->findMerchantOrder($merchantOrderCriteriaTransfer);

        return $merchantOrderTransfer;
    }

    /**
     * @phpstan-param \ArrayObject<int, \Generated\Shared\Transfer\ShipmentGroupTransfer> $groupedMerchantOrderItemsByShipment
     * @phpstan-param array<int|string, \Generated\Shared\Transfer\MerchantOrderItemTransfer> $merchantOrderItemsWithOrderItemIdKey
     *
     * @phpstan-return array<int|string, array<int|string, string>>
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
            $events[$shipmentGroupTransfer->getShipment()->getIdSalesShipment()] = array_unique($eventsForGroup);
        }

        return $events;
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
    protected function renderActions(Request $request, array $data, MerchantOrderTransfer $merchantOrderTransfer): array
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

    /**
     * @phpstan-return array<int|string, \Generated\Shared\Transfer\MerchantOrderItemTransfer>
     *
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     *
     * @return array
     */
    protected function groupMerchantOrderItemsByIdSalesOrderItem(MerchantOrderTransfer $merchantOrderTransfer): array
    {
        $groupedOrderItemsWithOrderItemIdKey = [];

        foreach ($merchantOrderTransfer->getMerchantOrderItems() as $merchantOrderItemTransfer) {
            $groupedOrderItemsWithOrderItemIdKey[$merchantOrderItemTransfer->getOrderItem()->getIdSalesOrderItem()] = $merchantOrderItemTransfer;
        }

        return $groupedOrderItemsWithOrderItemIdKey;
    }
}
