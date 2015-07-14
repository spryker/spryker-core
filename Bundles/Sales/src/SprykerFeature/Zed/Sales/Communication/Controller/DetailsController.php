<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Sales\Communication\SalesDependencyContainer;
use SprykerFeature\Zed\Sales\Persistence\SalesQueryContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use SprykerFeature\Zed\Sales\Business\SalesFacade;

/**
 * @method SalesDependencyContainer getDependencyContainer()
 * @method SalesFacade getFacade()
 * @method SalesQueryContainerInterface getQueryContainer()
 */
class DetailsController extends AbstractController
{

    /**
     * @param Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $orderId = $request->get('id-sales-order');

        $orderEntity = $this->getQueryContainer()->querySalesOrderById($orderId)->findOne();
        $orderItems = $this->getQueryContainer()->querySalesOrderItemsWithState($orderId)->find();
        $events = $this->getFacade()->getArrayWithManualEvents($orderId);
        $allEvents = $this->groupEvents($events);
        $expenses = $this->getQueryContainer()->querySalesExpensesByOrderId($orderId)->find();

        return [
            'idOrder' => $orderId,
            'orderDetails' => $orderEntity,
            'orderItems' => $orderItems,
            'events' => $events,
            'allEvents' => $allEvents,
            'expenses' => $expenses,
        ];
    }

    /**
     * @param $events
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
