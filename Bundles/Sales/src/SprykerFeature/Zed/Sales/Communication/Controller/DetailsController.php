<?php

namespace SprykerFeature\Zed\Sales\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Sales\Communication\SalesDependencyContainer;
use Symfony\Component\HttpFoundation\Request;
use SprykerFeature\Zed\Sales\Business\SalesFacade;

/**
 * @method SalesDependencyContainer getDependencyContainer()
 * @method SalesFacade getFacade()
 */
class DetailsController extends AbstractController
{
    public function indexAction(Request $request)
    {
        $orderId = $request->get('id');

        // @todo to come back later and make this functional
        $orderDetails = $this->getFacade()
            ->createOrderDetailsModel()
            ->getOrderDetailsByOrderId($orderId)
        ;

//        $this->getDependencyContainer()->create

        $orderItems = $this->getFacade()->getOrderItemsArrayByOrderId($orderId);

//        $orderItemsGrid = $this->getDependencyContainer()
//            ->getOrdersItemsGridByOrderId($orderId, $request)
//        ;
//
//        $orderItems = $orderItemsGrid->renderData();
//
//        $totalItems = 0;
//        $totalPrice = 0;
//
//        foreach ($orderItems['content']['rows'] as $item) {
//            $totalItems += $item['qty'];
//            $totalPrice += ($item['price_to_pay'] * $item['qty']);
//        }
//
//        $orderItems['content']['total'] = [
//            'total_price_to_pay' => $totalPrice,
//            'total_qty' => $totalItems,
//        ];

        return [
            'orderId' => $orderId,
            'orderDetails' => $orderDetails,
            'orderItems' => $orderItems,
        ];
    }
}
