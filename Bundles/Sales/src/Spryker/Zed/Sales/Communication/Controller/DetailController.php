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


//        $commentsList = $this->getSubRequest($request, '/sales/comment/list');
        $blockData = $this->getBlockData($request);

        if ($blockData instanceof RedirectResponse) {
            return $blockData;
        }

        return [
            'events' => $events,
            'allEvents' => $allEvents,
            'distinctOrderStates' => $distinctOrderStates,
            'logs' => [],
            'refunds' => [],
            'order' => $orderTransfer,
//            'commentsList' => $commentsList->getContent(),
            'block' => $blockData,
        ];
    }

    protected function getBlockData(Request $request)
    {
        $data = [
            'listComments' => $this->getSubRequest($request, '/sales/comment/list', [
                SalesConfig::PARAM_IS_SALES_ORDER => $request->query->get(SalesConfig::PARAM_IS_SALES_ORDER),
            ]),
            'addComment' => $this->getSubRequest($request, '/sales/comment/add'),
        ];

//        $blockResponses = [];
//        foreach ($data as $block) {
//            $blockResponse = $this->getSubRequest($request, '');
//            if ($blockResponse instanceof RedirectResponse) {
//                return $blockResponse;
//            }
//            $blockResponses[] = $blockResponse;
//        }

        return $data;
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

    /**
     * @param Request $request
     * @param $url
     * @param array $parameters
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function getSubRequest(Request $request, $url, array $parameters = [])
    {
        $subRequestParameters = array_merge($parameters, $request->query->all());

        if ($request->getMethod() === Request::METHOD_POST) {
            $subRequestParameters = array_merge($parameters, $request->request->all());
        }

        $subRequest = Request::create(
            $url,
            $request->getMethod(),
            $subRequestParameters,
            $request->cookies->all(),
            $request->files->all(),
            $request->server->all()
        );

        $urlChunks = explode('/', trim($url, '/'));

//        dump($request);
//        die;

        dump($urlChunks);

        $module = !empty($urlChunks[0]) ? $urlChunks[0] : 'Application';
        $controller = !empty($urlChunks[1]) ? $urlChunks[1] : 'Index';
        $action = !empty($urlChunks[2]) ? $urlChunks[2] : 'index';

//        $subRequest->request->set('message', 'ads');

        $subRequest->attributes->set('module', $module);
        $subRequest->attributes->set('controller', $controller);
        $subRequest->attributes->set('action', $action);

        dump($subRequest);

        $res = $this->getApplication()->handle($subRequest, HttpKernelInterface::SUB_REQUEST, true);

        return $res;
    }

}
