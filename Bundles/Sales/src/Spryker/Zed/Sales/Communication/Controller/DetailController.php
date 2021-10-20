<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Communication\Controller;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\Sales\SalesConfig;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Sales\Communication\SalesCommunicationFactory getFactory()
 * @method \Spryker\Zed\Sales\Business\SalesFacadeInterface getFacade()
 * @method \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Sales\Persistence\SalesRepositoryInterface getRepository()
 */
class DetailController extends AbstractController
{
    /**
     * @var string
     */
    protected const PARAM_ID_SALES_ORDER = 'id-sales-order';

    /**
     * @var string
     */
    public const ROUTE_REDIRECT = '/sales/detail';

    /**
     * @uses \Spryker\Zed\Http\Communication\Plugin\Application\HttpApplicationPlugin::SERVICE_SUB_REQUEST
     * @var string
     */
    protected const SERVICE_SUB_REQUEST = 'sub_request';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    public function indexAction(Request $request)
    {
        $idSalesOrder = $this->castId($request->query->getInt(SalesConfig::PARAM_ID_SALES_ORDER));

        $orderTransfer = $this->getFacade()->findOrderByIdSalesOrder($idSalesOrder);

        if ($orderTransfer === null) {
            $this->addErrorMessage('Sales order #%d not found.', ['%d' => $idSalesOrder]);

            return $this->redirectResponse(Url::generate('/sales')->build());
        }

        $distinctOrderStates = $this->getFacade()->getDistinctOrderStates($idSalesOrder);
        $eventsGroupedByShipment = $this->getFactory()->getOmsFacade()->getGroupedDistinctManualEventsByIdSalesOrder($idSalesOrder);
        $eventsGroupedByItem = $this->getFactory()->getOmsFacade()->getManualEventsByIdSalesOrder($idSalesOrder);
        $orderItemSplitFormCollection = $this->getFactory()->createOrderItemSplitFormCollection($orderTransfer->getItems());
        $events = $this->getFactory()->getOmsFacade()->getDistinctManualEventsByIdSalesOrder($idSalesOrder);

        $blockResponseData = $this->renderSalesDetailBlocks($request, $orderTransfer);
        if ($blockResponseData instanceof RedirectResponse) {
            return $blockResponseData;
        }

        $groupedOrderItems = $this->getFacade()
            ->getUniqueItemsFromOrder($orderTransfer);

        return array_merge([
            'eventsGroupedByItem' => $eventsGroupedByItem,
            'events' => $events,
            'eventsGroupedByShipment' => $eventsGroupedByShipment,
            'distinctOrderStates' => $distinctOrderStates,
            'order' => $orderTransfer,
            'orderItemSplitFormCollection' => $orderItemSplitFormCollection,
            'groupedOrderItems' => $groupedOrderItems,
            'changeStatusRedirectUrl' => $this->createRedirectLink($idSalesOrder),
            'tableColumnHeaders' => $this->getFactory()->createOrderItemsTableExpander()->getColumnHeaders(),
            'tableColumnCellsContent' => $this->getFactory()->createOrderItemsTableExpander()->getColumnCellsContent($orderTransfer->getItems()),
        ], $blockResponseData);
    }

    /**
     * @param int $idSalesOrder
     *
     * @return string
     */
    protected function createRedirectLink(int $idSalesOrder): string
    {
        $redirectUrlParams = [
            static::PARAM_ID_SALES_ORDER => $idSalesOrder,
        ];

        return Url::generate(static::ROUTE_REDIRECT, $redirectUrlParams);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    protected function renderSalesDetailBlocks(Request $request, OrderTransfer $orderTransfer)
    {
        $addCommentBlock = $this->handleSubRequest($request, '/sales/comment/add');

        if ($addCommentBlock instanceof RedirectResponse) {
            return $addCommentBlock;
        }

        $blockData = $this->renderMultipleActions(
            $request,
            $this->getFactory()->getSalesDetailExternalBlocksUrls(),
            $orderTransfer,
        );

        return [
            'add_comments' => $addCommentBlock,
            'blocks' => $blockData,
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param array $data
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array
     */
    protected function renderMultipleActions(Request $request, array $data, OrderTransfer $orderTransfer)
    {
        $subRequest = clone $request;
        $subRequest->setMethod(Request::METHOD_POST);
        /** @var array $orderTransfer */
        $subRequest->request->set('orderTransfer', $orderTransfer);

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
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|string
     */
    protected function handleSubRequest(Request $request, $blockUrl)
    {
        $blockResponse = $this->getSubRequestHandler()->handleSubRequest($request, $blockUrl);
        if ($blockResponse instanceof RedirectResponse) {
            return $blockResponse;
        }

        return (string)$blockResponse->getContent();
    }

    /**
     * @return \Spryker\Zed\Application\Business\Model\Request\SubRequestHandlerInterface
     */
    protected function getSubRequestHandler()
    {
        return $this->getApplication()->get(static::SERVICE_SUB_REQUEST);
    }
}
