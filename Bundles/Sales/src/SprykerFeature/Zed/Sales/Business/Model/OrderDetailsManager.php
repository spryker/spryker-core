<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Business\Model;

use SprykerFeature\Zed\Oms\Business\OmsFacade;
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
     * @param CustomerForm $customerForm
     * @param int $idOrder
     *
     * @return SpySalesOrder
     */
    public function updateOrderCustomerData(CustomerForm $customerForm, $idOrder)
    {
        $data = $customerForm->getData();

        $orderEntity = $this->queryContainer
            ->querySalesOrderById($idOrder)
            ->findOne()
        ;

        $orderEntity
            ->setFirstName($data[CustomerForm::FIRST_NAME])
            ->setLastName($data[CustomerForm::LAST_NAME])
            ->setEmail($data[CustomerForm::EMAIL])
            ->setSalutation($data[CustomerForm::SALUTATION])
        ;

        $orderEntity->save();

        return $orderEntity;
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
