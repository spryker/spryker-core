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
     * @param $idOrder
     *
     * @return array
     */
    public function getArrayWithManualEvents($idOrder)
    {
        $orderItems = $this->queryContainer->querySalesOrderItemsByIdSalesOrder($idOrder)->find();

        $events = [];
        foreach($orderItems as $i => $orderItem) {
            $events[$orderItem->getIdSalesOrderItem()] = $this->omsFacade->getManualEvents($orderItem->getIdSalesOrderItem());
        }

        return $events;
    }
}
