<?php

namespace SprykerTest\Zed\Sales\Business\Helper;

use Codeception\Module;
use DateTime;
use Orm\Zed\Customer\Persistence\SpyCustomer;
use Orm\Zed\Oms\Persistence\SpyOmsOrderItemState;
use Orm\Zed\Oms\Persistence\SpyOmsOrderProcess;
use Orm\Zed\Sales\Persistence\SpySalesExpense;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddress;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class Business extends Module
{

    const DEFAULT_OMS_PROCESS_NAME = 'test';
    const DEFAULT_ITEM_STATE = 'test';

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    public function create()
    {
        $salesOrderAddressEntity = $this->createSalesOrderAddress();
        $omsStateEntity = $this->createOmsState();
        $omsProcessEntity = $this->createOmsProcess();
        $salesOrderEntity = $this->createSpySalesOrderEntity($salesOrderAddressEntity);
        $this->createSalesExpense($salesOrderEntity);

        $this->createOrderItem(
            $omsStateEntity,
            $salesOrderEntity,
            $omsProcessEntity,
            1,
            500,
            19
        );

        $this->createOrderItem(
            $omsStateEntity,
            $salesOrderEntity,
            $omsProcessEntity,
            1,
            800,
            19
        );

        return $salesOrderEntity;
    }

    /**
     * @param \Orm\Zed\Oms\Persistence\SpyOmsOrderItemState $omsStateEntity
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     * @param \Orm\Zed\Oms\Persistence\SpyOmsOrderProcess $omsOrderProcessEntity
     * @param int $quantity
     * @param int $grossPrice
     * @param int $taxRate
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItem
     */
    protected function createOrderItem(
        SpyOmsOrderItemState $omsStateEntity,
        SpySalesOrder $salesOrderEntity,
        SpyOmsOrderProcess $omsOrderProcessEntity,
        $quantity,
        $grossPrice,
        $taxRate
    ) {
        $salesOrderItem = new SpySalesOrderItem();
        $salesOrderItem->setGrossPrice($grossPrice);
        $salesOrderItem->setQuantity($quantity);
        $salesOrderItem->setSku('123');
        $salesOrderItem->setName('test1');
        $salesOrderItem->setTaxRate($taxRate);
        $salesOrderItem->setFkOmsOrderItemState($omsStateEntity->getIdOmsOrderItemState());
        $salesOrderItem->setProcess($omsOrderProcessEntity);
        $salesOrderItem->setFkSalesOrder($salesOrderEntity->getIdSalesOrder());
        $salesOrderItem->save();

        return $salesOrderItem;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderAddress $salesOrderAddressEntity
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    protected function createSpySalesOrderEntity(SpySalesOrderAddress $salesOrderAddressEntity)
    {
        $shipmentMethodEntity = SpyShipmentMethodQuery::create()->findOne();

        $customerEntity = $this->createCustomer();

        $salesOrderEntity = new SpySalesOrder();
        $salesOrderEntity->setCustomer($customerEntity);
        $salesOrderEntity->setBillingAddress($salesOrderAddressEntity);
        $salesOrderEntity->setShippingAddress(clone $salesOrderAddressEntity);
        $salesOrderEntity->setShipmentMethod($shipmentMethodEntity);
        $salesOrderEntity->setOrderReference('123');
        $salesOrderEntity->save();

        return $salesOrderEntity;
    }

    /**
     * @return \Orm\Zed\Customer\Persistence\SpyCustomer
     */
    protected function createCustomer()
    {
        $customerEntity = new SpyCustomer();
        $customerEntity->setFirstName('First');
        $customerEntity->setLastName('Last');
        $customerEntity->setCompany('Company');
        $customerEntity->setEmail('email@email.tld');
        $customerEntity->setCustomerReference('testing-customer');
        $customerEntity->save();

        return $customerEntity;
    }

    /**
     * @return \Orm\Zed\Oms\Persistence\SpyOmsOrderItemState
     */
    protected function createOmsState()
    {
        $omsStateEntity = new SpyOmsOrderItemState();
        $omsStateEntity->setName(self::DEFAULT_ITEM_STATE);
        $omsStateEntity->save();

        return $omsStateEntity;
    }

    /**
     * @return \Orm\Zed\Oms\Persistence\SpyOmsOrderProcess
     */
    protected function createOmsProcess()
    {
        $omsProcessEntity = new SpyOmsOrderProcess();
        $omsProcessEntity->setName(self::DEFAULT_OMS_PROCESS_NAME);
        $omsProcessEntity->save();

        return $omsProcessEntity;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     *
     * @return void
     */
    protected function createSalesExpense(SpySalesOrder $salesOrderEntity)
    {
        $salesExpenseEntity = new SpySalesExpense();
        $salesExpenseEntity->setName('shipping test');
        $salesExpenseEntity->setTaxRate(19);
        $salesExpenseEntity->setGrossPrice(100);
        $salesExpenseEntity->setFkSalesOrder($salesOrderEntity->getIdSalesOrder());
        $salesExpenseEntity->save();
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderAddress
     */
    protected function createSalesOrderAddress()
    {
        $salesOrderAddressEntity = new SpySalesOrderAddress();
        $salesOrderAddressEntity->setAddress1(1);
        $salesOrderAddressEntity->setAddress2(2);
        $salesOrderAddressEntity->setSalutation('Mr');
        $salesOrderAddressEntity->setCellPhone('123456789');
        $salesOrderAddressEntity->setCity('City');
        $salesOrderAddressEntity->setCreatedAt(new DateTime());
        $salesOrderAddressEntity->setUpdatedAt(new DateTime());
        $salesOrderAddressEntity->setComment('Comment');
        $salesOrderAddressEntity->setDescription('Description');
        $salesOrderAddressEntity->setCompany('Company');
        $salesOrderAddressEntity->setFirstName('FirstName');
        $salesOrderAddressEntity->setLastName('LastName');
        $salesOrderAddressEntity->setFkCountry(1);
        $salesOrderAddressEntity->setEmail('Email');
        $salesOrderAddressEntity->setZipCode(12345);
        $salesOrderAddressEntity->save();

        return $salesOrderAddressEntity;
    }

}
