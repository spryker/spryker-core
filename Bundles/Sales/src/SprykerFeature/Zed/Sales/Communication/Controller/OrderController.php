<?php

namespace SprykerFeature\Zed\Sales\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Sales\SalesDependencyProvider;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method SalesDependencyContainer getDependencyContainer()
 */
class OrderController extends AbstractController
{

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function triggerAction(Request $request)
    {
        $orderItemId = intval($request->request->get('id', 0));
        $event = $request->request->get('event', null);

        $this->getDependencyContainer()
            ->getOmsFacade()
            ->triggerEventForOrderItems($event, [$orderItemId])
        ;

        $orderId = $this->getFacade()
            ->createOrderDetailsModel()
            ->getOrderIdByOrderItemById($orderItemId)
        ;

        return $this->redirectResponse('/sales/details?id=' . $orderId);
    }
}
