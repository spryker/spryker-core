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
    protected const PARAM_ID_SALES_ORDER = 'id-sales-order';
    public const ROUTE_REDIRECT = '/sales/detail';

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

        return array_merge([
            'eventsGroupedByItem' => $eventsGroupedByItem,
            'events' => $events,
            'distinctOrderStates' => $distinctOrderStates,
            'order' => $orderTransfer,
            'orderItemSplitFormCollection' => $orderItemSplitFormCollection,
            'changeStatusRedirectUrl' => $this->createRedirectLink($idSalesOrder),
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
}
