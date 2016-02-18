<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Oms\Communication\Controller;

use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Oms\Business\OmsFacade getFacade()
 * @method \Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface getQueryContainer()
 */
class TriggerController extends AbstractController
{

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function triggerEventForOrderItemsAction(Request $request)
    {
        $idOrderItem = $request->query->get('id-sales-order-item');
        $event = $request->query->get('event');
        $redirect = $request->query->get('redirect', '/');

        $this->getFacade()->triggerEventForOrderItems($event, [$idOrderItem]);

        return $this->redirectResponse($redirect);
    }

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function triggerEventForOrderAction(Request $request)
    {
        $idOrder = $request->query->get('id-sales-order');
        $event = $request->query->get('event');
        $redirect = $request->query->get('redirect', '/');
        $itemsList = $request->query->get('items');

        $orderItems = $this->getOrderItemsToTriggerAction($idOrder, $itemsList);

        $this->getFacade()->triggerEvent($event, $orderItems, []);

        return $this->redirectResponse($redirect);
    }

    /**
     * @param int $idOrder
     * @param array|null $itemsList
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItem[]|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function getOrderItemsToTriggerAction($idOrder, $itemsList = null)
    {
        $query = $this->getQueryContainer()->querySalesOrderItemsByIdOrder($idOrder);

        if (is_array($itemsList) && count($itemsList) > 0) {
            $query->filterByIdSalesOrderItem($itemsList, Criteria::IN);
        }

        $orderItems = $query->find();

        return $orderItems;
    }

}
