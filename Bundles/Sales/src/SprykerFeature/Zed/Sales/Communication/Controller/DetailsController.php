<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Sales\Communication\SalesDependencyContainer;
use SprykerFeature\Zed\Sales\Communication\Table\DetailsTable;
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
        $idOrder = $request->get('id-sales-order');

        $orderEntity = $this->getQueryContainer()->querySalesOrderById($idOrder)->findOne();
        $orderItems = $this->getQueryContainer()->querySalesOrderItemsWithState($idOrder)->find();
        $events = $this->getFacade()->getArrayWithManualEvents($idOrder);
        $allEvents = $this->groupEvents($events);
        $gui['orderItemsTable'] = new DetailsTable();
        $gui['orderItemsTable']->prepareDate($orderItems);

        return [
            'idOrder' => $idOrder,
            'orderDetails' => $orderEntity,
            'orderItems' => $orderItems,
            'events' => $events,
            'all_events' => $allEvents,
            'gui' => $gui

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
