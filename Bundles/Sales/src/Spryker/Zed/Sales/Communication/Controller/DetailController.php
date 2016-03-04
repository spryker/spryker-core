<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Communication\Controller;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Spryker\Zed\Sales\SalesConfig;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \Spryker\Zed\Sales\Communication\SalesCommunicationFactory getFactory()
 * @method \Spryker\Zed\Sales\Business\SalesFacade getFacade()
 * @method \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface getQueryContainer()
 */
class DetailController extends AbstractController
{

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $idSalesOrder = $request->get(SalesConfig::PARAM_IS_SALES_ORDER); // TODO FW Use $this->castId(SalesConfig::PARAM_IS_SALES_ORDER) See #1409

        $orderTransfer = $this->getFacade()->getOrderByIdSalesOrder($idSalesOrder);

        $distinctOrderStates = $this->getFacade()->getDistinctOrderStates($idSalesOrder);
        $events = $this->getFactory()->getOmsFacade()->getDistinctManualEventsByIdSalesOrder($idSalesOrder);
        $eventsGroupedByItem = $this->getFactory()->getOmsFacade()->getManualEventsByIdSalesOrder($idSalesOrder);

        $blockResponseData =  $this->renderSalesDetailBlocks($request, $orderTransfer);
        if ($blockResponseData instanceof RedirectResponse) {
            return $blockResponseData;
        }

        return array_merge([
            'eventsGroupedByItem' => $eventsGroupedByItem,
            'events' => $events,
            'distinctOrderStates' => $distinctOrderStates,
            'order' => $orderTransfer,
        ], $blockResponseData);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array|string
     */
    protected function renderSalesDetailBlocks(Request $request, OrderTransfer $orderTransfer)
    {
        $addCommentBlock = $this->handleSubRequest($request, '/sales/comment/add');

        if ($addCommentBlock instanceof RedirectResponse) {
            return $addCommentBlock;
        }
        $request->attributes->set('orderTransfer', $orderTransfer);

        $blockData = $this->renderMultipleActions($request, $this->getFactory()->getSalesDetailExternalBlocksUrls());

        if ($blockData instanceof RedirectResponse) {
            return $blockData;
        }

        return [
            'add_comments' => $addCommentBlock,
            'blocks' => $blockData,
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param array $data
     *
     * @return array
     */
    protected function renderMultipleActions(Request $request, array $data)
    {
        $subRequest = clone $request;
        $subRequest->setMethod(Request::METHOD_POST);

        $responseData = [];
        /**
         * @var string $blockName
         * @var \Symfony\Component\HttpFoundation\Response $blockResponse
         */
        foreach ($data as $blockName => $blockUrl) {
            $responseData[$blockName] = $this->handleSubRequest($subRequest, $blockUrl);
        }

        return $responseData;
    }

}
