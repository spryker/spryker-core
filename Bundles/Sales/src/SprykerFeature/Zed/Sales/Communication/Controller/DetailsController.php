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

        return [
            'orderId' => $orderId,
            'orderDetails' => $orderDetails,
        ];
    }
}
