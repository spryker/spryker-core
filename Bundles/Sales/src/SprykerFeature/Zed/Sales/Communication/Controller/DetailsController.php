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
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

        $orderEntity = $this->getQueryContainer()
            ->querySalesOrderById($idOrder)
            ->findOne();

        if ($orderEntity === null) {
            throw new NotFoundHttpException('Record not found');
        }

        $orderItems = $this->getQueryContainer()
            ->querySalesOrderItemsWithState($idOrder)
            ->find();

        $orderItemSplitFormCollection = $this->getDependencyContainer()->getOrderItemSplitFormCollection($orderItems);

        $events = $this->getFacade()->getArrayWithManualEvents($idOrder);
        $allEvents = $this->groupEvents($events);
        $expenses = $this->getQueryContainer()
            ->querySalesExpensesByOrderId($idOrder)
            ->find();
        $shippingAddress = $this->getQueryContainer()
            ->querySalesOrderAddressById($orderEntity->getFkSalesOrderAddressShipping())
            ->findOne();
        if ($orderEntity->getFkSalesOrderAddressShipping() === $orderEntity->getFkSalesOrderAddressBilling()) {
            $billingAddress = $shippingAddress;
        } else {
            $billingAddress = $this->getQueryContainer()
                ->querySalesOrderAddressById($orderEntity->getFkSalesOrderAddressBilling())
                ->findOne();
        }

        return [
            'idOrder' => $idOrder,
            'orderDetails' => $orderEntity,
            'orderItems' => $orderItems,
            'events' => $events,
            'allEvents' => $allEvents,
            'expenses' => $expenses,
            'billingAddress' => $billingAddress,
            'shippingAddress' => $shippingAddress,
            'orderItemSplitFormCollection' => $orderItemSplitFormCollection->create(),
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
