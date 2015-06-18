<?php

namespace SprykerFeature\Zed\Sales\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Sales\SalesDependencyProvider;
use Symfony\Component\HttpFoundation\Request;

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

        $orderId = $this->getDependencyContainer()
            ->getProvidedDependency(SalesDependencyProvider::FACADE_OMS)
            ->getOrderIdByOrderItemById($orderItemId)
        ;

        echo '<pre>';
        var_dump($orderId);
        die;

        return $this->redirectResponse('/sales/details?id=' . $orderItemId);
    }
}
