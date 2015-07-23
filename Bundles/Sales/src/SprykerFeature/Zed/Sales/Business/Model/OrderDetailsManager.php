<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Business\Model;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\SalesAddressTransfer;
use SprykerFeature\Zed\Library\Copy;
use SprykerFeature\Zed\Oms\Business\OmsFacade;
use SprykerFeature\Zed\Sales\Communication\Form\AddressForm;
use SprykerFeature\Zed\Sales\Communication\Form\CustomerForm;
use SprykerFeature\Zed\Sales\Persistence\SalesQueryContainerInterface;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder;

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
     * @param OrderTransfer $orderTransfer
     * @param int $idOrder
     *
     * @return SpySalesOrder
     */
    public function updateOrderCustomer(OrderTransfer $orderTransfer, $idOrder)
    {
        $orderEntity = $this->queryContainer
            ->querySalesOrderById($idOrder)
            ->findOne()
        ;

        Copy::transferToEntity($orderTransfer, $orderEntity);

        $orderEntity->save();

        return $orderEntity;
    }

    /**
     * @param SalesAddressTransfer $addressTransfer
     * @param int $idAddress
     *
     * @return SpySalesAddress
     */
    public function updateOrderAddress(SalesAddressTransfer $addressTransfer, $idAddress)
    {
        $addressEntity = $this->queryContainer
            ->querySalesOrderAddressById($idAddress)
            ->findOne();

        Copy::transferToEntity($addressTransfer, $addressEntity);

        $addressEntity->save();

        return $addressEntity;
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

    /**
     * @param int $idOrder
     *
     * @return array
     */
    public function getAggregateState($idOrder)
    {
        $orderItems = $this->queryContainer->querySalesOrderItemsByIdSalesOrder($idOrder)->find();

        $status = [];
        foreach($orderItems as $i => $orderItem) {
            $status[$orderItem->getIdSalesOrderItem()] = $orderItem->getState()->getName();
        }

        return $status;
    }

}
