<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Business\Model;

use SprykerFeature\Zed\Oms\Business\OmsFacade;
use SprykerFeature\Zed\Sales\Persistence\SalesQueryContainerInterface;

class OrderDetailsManager
{
    /**
     * @var SalesQueryContainerInterface
     */
    protected $queryContainer;

    protected $omsFacade;

    /**
     * @param SalesQueryContainerInterface $queryContainer
     */
    public function __construct(SalesQueryContainerInterface $queryContainer, OmsFacade $omsFacade)
    {
        $this->queryContainer = $queryContainer;
        $this->omsFacade = $omsFacade;
    }

    /**
     * @param int $orderItemId
     *
     * @return int
     */
    public function getOrderIdByOrderItemById($orderItemId)
    {
        $orderItem = $this->queryContainer->queryOrderItemById($orderItemId)->findOne();

        return $orderItem->getFkSalesOrder();
    }

    /**
     * @param $idOrder
     * @return array
     */
    public function getArrayWithManualEvents($idOrder)
    {
        $orderItems = $this->queryContainer->queryOrderItems($idOrder)->find();

        $events = [];
        foreach($orderItems as $i => $orderItem) {
            $events[$orderItem->getIdSalesOrderItem()] = $this->omsFacade->getManualEvents($orderItem->getIdSalesOrderItem());
        }
        return $events;
    }

    /**
     * @param int $orderItems
     *
     * @return array
     */
    protected function getOrderItemsListAndTotalDetails($orderItems)
    {
        $orderItemsList = [];

        $totalItems = $totalPrice = 0;

        foreach ($orderItems as $item) {
            $itemId = $item->getIdSalesOrderItem();

            $orderItemsList[$itemId] = $item->toArray();
            $orderItemsList[$itemId]['accepts'] = $this->getManualStateItemsByItemId($itemId);
            $orderItemsList[$itemId]['current_state'] = $item->getState()->getName();

            $totalItems += $item->getQty();
            $totalPrice += $item->getPriceToPay() * $item->getQty();
        }

        return [$orderItemsList, $totalItems, $totalPrice];
    }

    /**
     * @param $orderId
     *
     * @return SpySalesOrder
     */
    public function getOrderDetailsByOrderId($orderId)
    {
        $orderDetails = $this->queryContainer->querySalesById($orderId)->findOne();

        return $orderDetails;
    }

    /**
     * @param int $itemId
     *
     * @return array
     */
    protected function getManualStateItemsByItemId($itemId)
    {
        $manualStates = $this->omsFacade->getManualEvents($itemId);

        $availableStates = [];
        foreach ($manualStates as $stateItem) {
            $availableStates[] = [
                'value' => $stateItem,
                'label' => $stateItem,
            ];
        }

        return $availableStates;
    }
}
