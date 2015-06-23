<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

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

    /**
     * @param Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $orderId = intval($request->get('id'));

        $orderDetails = $this->getFacade()
            ->createOrderDetailsModel()
            ->getOrderDetailsByOrderId($orderId)
        ;

        $orderItems = $this->getFacade()->getOrderItemsArrayByOrderId($orderId);

        return [
            'orderId' => $orderId,
            'orderDetails' => $orderDetails,
            'orderItems' => $orderItems,
        ];
    }
}
