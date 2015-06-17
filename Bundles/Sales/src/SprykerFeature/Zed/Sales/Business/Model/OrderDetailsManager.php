<?php

namespace SprykerFeature\Zed\Sales\Business\Model;

use SprykerFeature\Zed\Oms\Business\OmsFacade;
use SprykerFeature\Zed\Sales\Persistence\SalesQueryContainerInterface;

class OrderDetailsManager
{
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
     * @param int $orderId
     *
     * @return array
     */
    public function getOrderItemsArrayByOrderId($orderId)
    {
        $orderItems = $this->queryContainer->queryOrderItems($orderId)->find();

        $orderItemsList = [];

        $totalItems = 0;
        $totalPrice = 0;

        foreach ($orderItems as $item) {
            $itemId =$item->getIdSalesOrderItem();

            $manualStates = $this->omsFacade->getManualEvents($itemId);

            $availableStates = [];
            foreach ($manualStates as $stateItem) {
                $availableStates[] = json_encode([
                    'value' => $stateItem,
                    'label' => $stateItem,
                ]);
            }

            $orderItemsList[$itemId] = $item->toArray();
            $orderItemsList[$itemId]['accepts'] = implode(',', $availableStates);

            $totalItems += $item->getQty();
            $totalPrice += $item->getPriceToPay() * $item->getQty();
        }

        return [
            'content' => [
                'rows' => $orderItemsList,
                'total' => [
                    'total_price_to_pay' => $totalPrice,
                    'total_qty' => $totalItems,
                ]
            ]
        ];
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
}
