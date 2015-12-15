<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Oms\Communication\Controller;

use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Spryker\Zed\Oms\Business\OmsFacade;
use Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method OmsFacade getFacade()
 * @method OmsQueryContainerInterface getQueryContainer()
 */
class TriggerController extends AbstractController
{

    public function triggerEventForOrderItemsAction(Request $request)
    {
        $idOrderItem = $request->query->get('id-sales-order-item');
        $event = $request->query->get('event');
        $redirect = $request->query->get('redirect', '/');

        $this->getFacade()->triggerEventForOrderItems($event, [$idOrderItem]);

        return $this->redirectResponse($redirect);
    }

    public function triggerEventForOrderAction(Request $request)
    {
        $idOrder = $request->query->get('id-sales-order');
        $event = $request->query->get('event');
        $redirect = $request->query->get('redirect', '/');

        $orderItems = $this->getQueryContainer()->querySalesOrderItemsByIdOrder($idOrder)->find();

        $this->getFacade()->triggerEvent($event, $orderItems, []);

        return $this->redirectResponse($redirect);
    }

}
