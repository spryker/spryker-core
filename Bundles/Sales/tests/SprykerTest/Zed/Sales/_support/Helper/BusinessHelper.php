<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Sales\Helper;

use Codeception\Module;
use DateTime;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\Transfer\ItemTransfer;
use Orm\Zed\Customer\Persistence\SpyCustomer;
use Orm\Zed\Oms\Persistence\SpyOmsOrderItemState;
use Orm\Zed\Oms\Persistence\SpyOmsOrderProcess;
use Orm\Zed\Sales\Persistence\SpySalesExpense;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddress;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Orm\Zed\Sales\Persistence\SpySalesOrderTotals;
use Orm\Zed\Sales\Persistence\SpySalesShipment;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class BusinessHelper extends Module
{
    public const DEFAULT_OMS_PROCESS_NAME = 'Test01';
    public const DEFAULT_ITEM_STATE = 'test';

    protected const ORDER_ITEM_QTY = 1;
    protected const ORDER_ITEM_GROSS_PRICE_1 = 500;
    protected const ORDER_ITEM_GROSS_PRICE_2 = 800;
    protected const ORDER_ITEM_TAX_RATE = 19;

    /**
     * @deprecated Use `BusinessHelper::haveSalesOrderEntity` instead.
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    public function create()
    {
        return $this->haveSalesOrderEntity();
    }

    /**
     * @param iterable|array $itemTransfers
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    public function haveSalesOrderEntity(iterable $itemTransfers = []): SpySalesOrder
    {
        $salesOrderAddressEntity = $this->createSalesOrderAddress();
        $omsStateEntity = $this->createOmsState();
        $omsProcessEntity = $this->createOmsProcess();
        $salesOrderEntity = $this->createSpySalesOrderEntity($salesOrderAddressEntity);
        $salesExpenseEntity = $this->createSalesExpense($salesOrderEntity);

        $this->createOrderItems(
            $omsStateEntity,
            $salesOrderEntity,
            $omsProcessEntity,
            $itemTransfers
        );

        $this->createSpySalesShipment($salesOrderEntity->getIdSalesOrder(), $salesExpenseEntity->getIdSalesExpense());
        $this->createOrderTotals($salesOrderEntity->getIdSalesOrder());

        return $salesOrderEntity;
    }

    /**
     * @param \Orm\Zed\Oms\Persistence\SpyOmsOrderItemState $omsStateEntity
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     * @param \Orm\Zed\Oms\Persistence\SpyOmsOrderProcess $omsOrderProcessEntity
     * @param iterable|array $itemTransfers
     *
     * @return iterable|\Orm\Zed\Sales\Persistence\SpySalesOrderItem[]
     */
    protected function createOrderItems(
        SpyOmsOrderItemState $omsStateEntity,
        SpySalesOrder $salesOrderEntity,
        SpyOmsOrderProcess $omsOrderProcessEntity,
        iterable $itemTransfers = []
    ): iterable {
        if (count($itemTransfers) === 0) {
            return $this->createOrderItemsWithDefaultValues($omsStateEntity, $salesOrderEntity, $omsOrderProcessEntity);
        }

        return $this->createOrderItemsUsingItemTransfers(
            $omsStateEntity,
            $salesOrderEntity,
            $omsOrderProcessEntity,
            $itemTransfers
        );
    }

    /**
     * @param \Orm\Zed\Oms\Persistence\SpyOmsOrderItemState $omsStateEntity
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     * @param \Orm\Zed\Oms\Persistence\SpyOmsOrderProcess $omsOrderProcessEntity
     * @param iterable $itemTransfers
     *
     * @return iterable|\Orm\Zed\Sales\Persistence\SpySalesOrderItem[]
     */
    protected function createOrderItemsUsingItemTransfers(
        SpyOmsOrderItemState $omsStateEntity,
        SpySalesOrder $salesOrderEntity,
        SpyOmsOrderProcess $omsOrderProcessEntity,
        iterable $itemTransfers
    ): iterable {
        $salesOrderItems = [];

        foreach ($itemTransfers as $itemTransfer) {
            $this->createOrderItem(
                $omsStateEntity,
                $salesOrderEntity,
                $omsOrderProcessEntity,
                $itemTransfer,
                static::ORDER_ITEM_QTY,
                static::ORDER_ITEM_GROSS_PRICE_1,
                static::ORDER_ITEM_TAX_RATE
            );
        }

        return $salesOrderItems;
    }

    /**
     * @param \Orm\Zed\Oms\Persistence\SpyOmsOrderItemState $omsStateEntity
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     * @param \Orm\Zed\Oms\Persistence\SpyOmsOrderProcess $omsOrderProcessEntity
     *
     * @return iterable|\Orm\Zed\Sales\Persistence\SpySalesOrderItem[]
     */
    protected function createOrderItemsWithDefaultValues(
        SpyOmsOrderItemState $omsStateEntity,
        SpySalesOrder $salesOrderEntity,
        SpyOmsOrderProcess $omsOrderProcessEntity
    ): iterable {
        $salesOrderItems = [];

        $salesOrderItems[] = $this->createOrderItem(
            $omsStateEntity,
            $salesOrderEntity,
            $omsOrderProcessEntity,
            (new ItemBuilder())->build(),
            static::ORDER_ITEM_QTY,
            static::ORDER_ITEM_GROSS_PRICE_1,
            static::ORDER_ITEM_TAX_RATE
        );

        $salesOrderItems[] = $this->createOrderItem(
            $omsStateEntity,
            $salesOrderEntity,
            $omsOrderProcessEntity,
            (new ItemBuilder())->build(),
            static::ORDER_ITEM_QTY,
            static::ORDER_ITEM_GROSS_PRICE_2,
            static::ORDER_ITEM_TAX_RATE
        );

        return $salesOrderItems;
    }

    /**
     * @param \Orm\Zed\Oms\Persistence\SpyOmsOrderItemState $omsStateEntity
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     * @param \Orm\Zed\Oms\Persistence\SpyOmsOrderProcess $omsOrderProcessEntity
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
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
        ItemTransfer $itemTransfer,
        $quantity,
        $grossPrice,
        $taxRate
    ) {
        $salesOrderItem = new SpySalesOrderItem();
        $salesOrderItem->setGrossPrice($grossPrice);
        $salesOrderItem->setQuantity($quantity);
        $salesOrderItem->setSku($itemTransfer->getSku());
        $salesOrderItem->setName($itemTransfer->getName());
        $salesOrderItem->setTaxRate($taxRate);
        $salesOrderItem->setFkOmsOrderItemState($omsStateEntity->getIdOmsOrderItemState());
        $salesOrderItem->setProcess($omsOrderProcessEntity);
        $salesOrderItem->setFkSalesOrder($salesOrderEntity->getIdSalesOrder());
        $salesOrderItem->setGroupKey($itemTransfer->getGroupKey());
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
        $customerEntity = $this->createCustomer();

        $salesOrderEntity = new SpySalesOrder();
        $salesOrderEntity->setCustomer($customerEntity);
        $salesOrderEntity->setBillingAddress($salesOrderAddressEntity);
        $salesOrderEntity->setShippingAddress(clone $salesOrderAddressEntity);
        $salesOrderEntity->setOrderReference('123');
        $salesOrderEntity->save();

        return $salesOrderEntity;
    }

    /**
     * @param int $idSalesOrder
     * @param int $idSalesExpense
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesShipment
     */
    protected function createSpySalesShipment($idSalesOrder, $idSalesExpense)
    {
        $salesShipmentEntity = new SpySalesShipment();
        $salesShipmentEntity->setDeliveryTime('1 h');
        $salesShipmentEntity->setCarrierName('Carrier name');
        $salesShipmentEntity->setName('Shipment name');
        $salesShipmentEntity->setFkSalesOrder($idSalesOrder);
        $salesShipmentEntity->setFkSalesExpense($idSalesExpense);

        $salesShipmentEntity->save();

        return $salesShipmentEntity;
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
     * @return \Orm\Zed\Sales\Persistence\SpySalesExpense
     */
    protected function createSalesExpense(SpySalesOrder $salesOrderEntity)
    {
        $salesExpenseEntity = new SpySalesExpense();
        $salesExpenseEntity->setName('shipping test');
        $salesExpenseEntity->setTaxRate(19);
        $salesExpenseEntity->setGrossPrice(100);
        $salesExpenseEntity->setFkSalesOrder($salesOrderEntity->getIdSalesOrder());
        $salesExpenseEntity->save();

        return $salesExpenseEntity;
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

    /**
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderTotals
     */
    protected function createOrderTotals($idSalesOrder)
    {
        $salesOrderTotalsEntity = new SpySalesOrderTotals();
        $salesOrderTotalsEntity->setSubtotal(1000);
        $salesOrderTotalsEntity->setGrandTotal(2500);
        $salesOrderTotalsEntity->setFkSalesOrder($idSalesOrder);

        $salesOrderTotalsEntity->save();

        return $salesOrderTotalsEntity;
    }
}
