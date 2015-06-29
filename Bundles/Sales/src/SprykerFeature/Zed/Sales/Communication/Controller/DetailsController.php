<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Sales\Communication\SalesDependencyContainer;
use SprykerFeature\Zed\Sales\Persistence\SalesQueryContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use SprykerFeature\Zed\Sales\Business\SalesFacade;

/**
 * @method SalesDependencyContainer getDependencyContainer()
 * @method SalesFacade getFacade()
 * @method SalesQueryContainerInterface getQueryContainer()
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
        $idOrder = $request->get('id');

        $orderEntity = $this->getQueryContainer()->querySalesById($idOrder)->findOne();
        $orderItems = $this->getQueryContainer()->queryOrderItemsWithState($idOrder)->find();
        $events = $this->getFacade()->getArrayWithManualEvents($idOrder);

        return [
            'idOrder' => $idOrder,
            'orderDetails' => $orderEntity,
            'orderItems' => $orderItems,
            'events' => $events
        ];
    }
}
