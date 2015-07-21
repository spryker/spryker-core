<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Business\Model;

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
     * @param AddressForm $addressForm
     * @param int $idAddress
     *
     * @return SpySalesAddressQuery
     */
    public function updateOrderAddressData(AddressForm $addressForm, $idAddress)
    {
        $data = $addressForm->getData();

        $address = $this->queryContainer
            ->querySalesOrderAddressById($idAddress)
            ->findOne();

        $address
            ->setFirstName($data[AddressForm::FIRST_NAME])
            ->setFirstName($data[AddressForm::FIRST_NAME])
            ->setMiddleName($data[AddressForm::MIDDLE_NAME])
            ->setLastName($data[AddressForm::LAST_NAME])
            ->setEmail($data[AddressForm::EMAIL])
            ->setAddress1($data[AddressForm::ADDRESS_1])
            ->setAddress2($data[AddressForm::ADDRESS_1])
            ->setAddress3($data[AddressForm::ADDRESS_1])
            ->setCompany($data[AddressForm::COMPANY])
            ->setCity($data[AddressForm::CITY])
            ->setZipCode($data[AddressForm::ZIP_CODE])
            ->setPoBox($data[AddressForm::PO_BOX])
            ->setPhone($data[AddressForm::PHONE])
            ->setCellPhone($data[AddressForm::CELL_PHONE])
            ->setDescription($data[AddressForm::DESCRIPTION])
            ->setComment($data[AddressForm::COMMENT])
        ;

        $address->save();

        return $address;
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
