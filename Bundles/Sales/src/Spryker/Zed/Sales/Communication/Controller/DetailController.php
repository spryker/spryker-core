<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Sales\Communication\Controller;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Spryker\Zed\Sales\SalesConfig;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * @method \Spryker\Zed\Sales\Communication\SalesCommunicationFactory getFactory()
 * @method \Spryker\Zed\Sales\Business\SalesFacade getFacade()
 * @method \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface getQueryContainer()
 */
class DetailController extends AbstractController // TODO FW No plural in controller names. Rename to DetailController
{

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $idSalesOrder = $request->get(SalesConfig::PARAM_IS_SALES_ORDER); // TODO FW Use $this->castId(SalesConfig::PARAM_IS_SALES_ORDER) See #1409

        $orderTransfer = new OrderTransfer();
        $orderTransfer->setIdSalesOrder($idSalesOrder);
        $orderTransfer = $this->getFacade()->getOrderDetails($orderTransfer); // TODO FW See comments in facade. Needs split into smaller parts
        $orderTransfer = $this->getFactory()->getSalesAggregator()->getOrderTotalByOrderTransfer($orderTransfer);

        $distinctOrderStates = $this->getFacade()->getDistinctOrderStates($idSalesOrder);

        $events = $this->getFactory()->getOmsFacade()->getManualEventsByIdSalesOrder($idSalesOrder);

        $allEvents = $this->groupEvents($events);
        //$logs = $this->getFacade()->getPaymentLogs($idSalesOrder); // TODO FW Needs another solution, see mails
        //$refunds = $this->getFacade()->getRefunds($idSalesOrder); // TODO FW Needs another solution, see mails

        $blockResponseData =  $this->renderSalesDetailBlocks($request, $orderTransfer);
        if ($blockResponseData instanceof RedirectResponse) {
            return $blockResponseData;
        }

        return array_merge([
            'events' => $events,
            'allEvents' => $allEvents,
            'distinctOrderStates' => $distinctOrderStates,
            'logs' => [],
            'refunds' => [],
            'order' => $orderTransfer,
        ], $blockResponseData);
    }

    /**
     * @param Request $request
     * @return array|string
     */
    protected function renderSalesDetailBlocks(Request $request, OrderTransfer $orderTransfer)
    {
        $addCommentBlock = $this->renderAction($request, '/sales/comment/add');

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
     * @param Request $request
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
         * @var Response $blockResponse
         */
        foreach ($data as $blockName => $blockUrl) {
            $responseData[$blockName] = $this->renderAction($subRequest, $blockUrl);
        }

        return $responseData;
    }

    /**
     * TODO FE By convention we dissallow protected methods in controller.
     *
     * @param array $events
     *
     * @return array
     */
    protected function groupEvents($events)
    {
        $allEvents = [];
        foreach ($events as $eventList) {
            $allEvents = array_merge($allEvents, $eventList);
        }

        return array_unique($allEvents);
    }

}
