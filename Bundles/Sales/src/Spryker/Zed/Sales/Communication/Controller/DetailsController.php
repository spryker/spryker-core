<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Sales\Communication\Controller;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Spryker\Zed\Sales\SalesConfig;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Sales\Communication\SalesCommunicationFactory getFactory()
 * @method \Spryker\Zed\Sales\Business\SalesFacade getFacade()
 * @method \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface getQueryContainer()
 */
class DetailsController extends AbstractController // TODO FW No plural in controller names. Rename to DetailController
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
        $logs = $this->getFacade()->getPaymentLogs($idSalesOrder); // TODO FW Needs another solution, see mails
        $refunds = $this->getFacade()->getRefunds($idSalesOrder); // TODO FW Needs another solution, see mails

        return [
            'events' => $events,
            'allEvents' => $allEvents,
            'distinctOrderStates' => $distinctOrderStates,
            'logs' => $logs,
            'refunds' => $refunds,
            'order' => $orderTransfer,
        ];
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
