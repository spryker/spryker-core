<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Communication\Controller;

use Generated\Shared\Transfer\ItemTransfer;
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
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
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
        $events = $this->getFactory()->getOmsFacade()->getDistinctManualEventsByIdSalesOrder($idSalesOrder);
        $eventsGroupedByItem = $this->getFactory()->getOmsFacade()->getManualEventsByIdSalesOrder($idSalesOrder);
        $orderItemSplitFormCollection = $this->getFactory()->createOrderItemSplitFormCollection($orderTransfer->getItems());

        $blockResponseData = $this->renderSalesDetailBlocks($request, $orderTransfer);
        if ($blockResponseData instanceof RedirectResponse) {
            return $blockResponseData;
        }

        $orderOmsTriggerFormCollection = $this->getOrderOmsTriggerFormCollection($orderTransfer, $events);
        $orderItemsOmsTriggerFormCollection = $this->getOrderItemsOmsTriggerFormCollection($orderTransfer, $eventsGroupedByItem);

        return array_merge([
            'distinctOrderStates' => $distinctOrderStates,
            'order' => $orderTransfer,
            'orderItemSplitFormCollection' => $orderItemSplitFormCollection,
            'orderOmsTriggerFormCollection' => $orderOmsTriggerFormCollection,
            'orderItemsOmsTriggerFormCollection' => $orderItemsOmsTriggerFormCollection,
        ], $blockResponseData);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array|string|\Symfony\Component\HttpFoundation\RedirectResponse
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
            $orderTransfer
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
        $subRequest->request->set('orderTransfer', $orderTransfer);

        $responseData = [];
        /*
         * @var string $blockName
         * @var \Symfony\Component\HttpFoundation\Response $blockResponse
         */
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
    protected function handleSubRequest(Request $request, $blockUrl)
    {
        $blockResponse = $this->getSubRequestHandler()->handleSubRequest($request, $blockUrl);
        if ($blockResponse instanceof RedirectResponse) {
            return $blockResponse;
        }

        return $blockResponse->getContent();
    }

    /**
     * @return \Spryker\Zed\Application\Business\Model\Request\SubRequestHandlerInterface
     */
    protected function getSubRequestHandler()
    {
        return $this->getApplication()['sub_request'];
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param array $eventsGroupedByItem
     *
     * @return array
     */
    protected function getOrderItemsOmsTriggerFormCollection(OrderTransfer $orderTransfer, array $eventsGroupedByItem): array
    {
        $orderItemsOmsTriggerFormCollection = [];

        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $idSalesOrderItem = $itemTransfer->getIdSalesOrderItem();

            $orderItemsOmsTriggerFormCollection[$idSalesOrderItem] = $this->getSingleOrderItemOmsTriggerFormCollection(
                $itemTransfer,
                $eventsGroupedByItem[$idSalesOrderItem]
            );
        }

        return $orderItemsOmsTriggerFormCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param string[] $events
     *
     * @return \Symfony\Component\Form\FormView[]
     */
    protected function getSingleOrderItemOmsTriggerFormCollection(ItemTransfer $itemTransfer, array $events): array
    {
        $orderItemOmsTriggerFormCollection = [];

        foreach ($events as $event) {
            $orderItemOmsTriggerFormCollection[$event] = $this->getFactory()
                ->getOrderItemOmsTriggerForm($itemTransfer, $event)
                ->createView();
        }

        return $orderItemOmsTriggerFormCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param string[] $events
     *
     * @return \Symfony\Component\Form\FormView[]
     */
    protected function getOrderOmsTriggerFormCollection(OrderTransfer $orderTransfer, array $events): array
    {
        $orderOmsTriggerFormCollection = [];

        foreach ($events as $event) {
            $orderOmsTriggerFormCollection[$event] = $this->getFactory()
                ->getOrderOmsTriggerForm($orderTransfer, $event)
                ->createView();
        }

        return $orderOmsTriggerFormCollection;
    }
}
