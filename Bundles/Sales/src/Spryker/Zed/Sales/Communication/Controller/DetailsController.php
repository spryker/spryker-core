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
class DetailsController extends AbstractController
{

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $idSalesOrder = $request->get(SalesConfig::PARAM_IS_SALES_ORDER);

        $orderTransfer = new OrderTransfer();
        $orderTransfer->setIdSalesOrder($idSalesOrder);
        $orderTransfer = $this->getFacade()->getOrderDetails($orderTransfer);
        $orderTransfer = $this->getFacade()->getOrderTotalByOrderTransfer($orderTransfer);

        $orderItemSplitFormCollection = $this->getFactory()->createOrderItemSplitFormCollection($orderTransfer->getItems());
        $uniqueOrderStates = $this->getFacade()->getUniqueOrderStates($idSalesOrder);
        $events = $this->getFacade()->getArrayWithManualEvents($idSalesOrder);
        $allEvents = $this->groupEvents($events);
        $logs = $this->getFacade()->getPaymentLogs($idSalesOrder);
        $refunds = $this->getFacade()->getRefunds($idSalesOrder);

        return [
            'events' => $events,
            'allEvents' => $allEvents,
            'uniqueOrderStates' => $uniqueOrderStates,
            'logs' => $logs,
            'refunds' => $refunds,
            'orderItemSplitFormCollection' => $orderItemSplitFormCollection,
            'order' => $orderTransfer,
        ];
    }

    /**
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
