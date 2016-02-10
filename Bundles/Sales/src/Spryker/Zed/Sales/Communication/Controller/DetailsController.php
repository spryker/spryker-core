<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Sales\Communication\Controller;

use Orm\Zed\Oms\Persistence\Map\SpyOmsOrderItemStateHistoryTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
        $idOrder = $request->query->get('id-sales-order');

        $orderEntity = $this->getQueryContainer()
            ->querySalesOrderById($idOrder)
            ->findOne();

        if ($orderEntity === null) {
            throw new NotFoundHttpException('Record not found');
        }

        $orderItems = $this->getQueryContainer()
            ->querySalesOrderItemsWithState($idOrder)
            ->find();

        foreach ($orderItems as $orderItem) {
            $criteria = new Criteria();
            $criteria->addDescendingOrderByColumn(SpyOmsOrderItemStateHistoryTableMap::COL_ID_OMS_ORDER_ITEM_STATE_HISTORY);
            $orderItem->getStateHistoriesJoinState($criteria);
            $orderItem->resetPartialStateHistories(false);
        }

        $orderItemSplitFormCollection = $this->getFactory()->createOrderItemSplitFormCollection($orderItems);

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

        $logs = $this->getFacade()->getPaymentLogs($idOrder);

        $refunds = $this->getFacade()->getRefunds($idOrder);

        $itemsInProgress = $this->getFactory()->getOmsFacade()->getItemsWithFlag($orderEntity, 'in progress');
        $itemsPaid = $this->getFactory()->getOmsFacade()->getItemsWithFlag($orderEntity, 'paid');
        $itemsCancelled = $this->getFactory()->getOmsFacade()->getItemsWithFlag($orderEntity, 'cancelled');

        return [
            'idOrder' => $idOrder,
            'orderDetails' => $orderEntity,
            'orderItems' => $orderItems,
            'events' => $events,
            'allEvents' => $allEvents,
            'expenses' => $expenses,
            'logs' => $logs,
            'refunds' => $refunds,
            'billingAddress' => $billingAddress,
            'shippingAddress' => $shippingAddress,
            'orderItemSplitFormCollection' => $orderItemSplitFormCollection,
            'itemsInProgress' => $itemsInProgress,
            'itemsPaid' => $itemsPaid,
            'itemsCancelled' => $itemsCancelled,
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
