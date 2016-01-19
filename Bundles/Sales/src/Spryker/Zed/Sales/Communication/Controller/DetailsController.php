<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Sales\Communication\Controller;

use Generated\Shared\Transfer\OrderTransfer;
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
        $idSalesOrder = $request->get('id-sales-order');

        $orderTransfer = $this->getFacade()->getOrderTotalsByIdSalesOrder($idSalesOrder);

        $salesOrderEntity = $this->getQueryContainer()
            ->querySalesOrderById($idSalesOrder)
            ->findOne();

        if ($salesOrderEntity === null) {
            throw new NotFoundHttpException(sprintf('Sales order with id "%d" not found!', $idSalesOrder));
        }

        foreach ($salesOrderEntity->getItems() as $orderItem) {
            $criteria = new Criteria();
            $criteria->addDescendingOrderByColumn(SpyOmsOrderItemStateHistoryTableMap::COL_ID_OMS_ORDER_ITEM_STATE_HISTORY);
            $orderItem->getStateHistoriesJoinState($criteria);
            $orderItem->resetPartialStateHistories(false);
        }


        $orderItemSplitFormCollection = $this->getFactory()->createOrderItemSplitFormCollection($salesOrderEntity->getItems());
        $events = $this->getFacade()->getArrayWithManualEvents($idSalesOrder);
        $allEvents = $this->groupEvents($events);

        $expenses = $this->getQueryContainer()
            ->querySalesExpensesByOrderId($idSalesOrder)
            ->find();

        $shippingAddress = $this->getQueryContainer()
            ->querySalesOrderAddressById($salesOrderEntity->getFkSalesOrderAddressShipping())
            ->findOne();

        if ($salesOrderEntity->getFkSalesOrderAddressShipping() === $salesOrderEntity->getFkSalesOrderAddressBilling()) {
            $billingAddress = $shippingAddress;
        } else {
            $billingAddress = $this->getQueryContainer()
                ->querySalesOrderAddressById($salesOrderEntity->getFkSalesOrderAddressBilling())
                ->findOne();
        }

        $logs = $this->getFacade()->getPaymentLogs($idSalesOrder);

        $refunds = $this->getFacade()->getRefunds($idSalesOrder);

        $itemsInProgress = $this->getFactory()->getOmsFacade()->getItemsWithFlag($salesOrderEntity, 'in progress');
        $itemsPaid = $this->getFactory()->getOmsFacade()->getItemsWithFlag($salesOrderEntity, 'paid');
        $itemsCancelled = $this->getFactory()->getOmsFacade()->getItemsWithFlag($salesOrderEntity, 'cancelled');

        return [
            'idOrder' => $idSalesOrder,
            'orderDetails' => $salesOrderEntity,
            'orderItems' => $salesOrderEntity->getItems(),
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
