<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Oms\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Oms\Persistence\SpyOmsOrderItemState;
use Orm\Zed\Oms\Persistence\SpyOmsOrderItemStateQuery;
use Orm\Zed\Oms\Persistence\SpyOmsOrderProcess;
use Orm\Zed\Oms\Persistence\SpyOmsOrderProcessQuery;
use Orm\Zed\Oms\Persistence\SpyOmsProductReservationQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddress;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\DecimalObject\Decimal;
use Spryker\Zed\Oms\Business\OmsFacadeInterface;
use Spryker\Zed\Store\Business\StoreFacadeInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Oms
 * @group Business
 * @group Facade
 * @group OmsFacadeSumReservedItemsTest
 * Add your own group annotations below this line
 */
class OmsFacadeSumReservedItemsTest extends Unit
{
    public const ORDER_ITEM_SKU = 'oms-reserverd-sku-test';
    public const NOT_RESERVED_ITEM_STATE_EXCEPT_PROCESS_3 = 'paid';
    public const STORE_NAME_DE = 'DE';

    /**
     * @var \SprykerTest\Zed\Oms\OmsBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->tester->resetReservedStatesCache();
        $this->tester->configureTestStateMachine(['Test01', 'Test02', 'Test03']);
    }

    /**
     * @return void
     */
    public function tearDown()
    {
        parent::tearDown();
        $this->tester->resetReservedStatesCache();
    }

    /**
     * @return void
     */
    public function testSumReservedItemsShouldSumAllItemsInReservedState(): void
    {
        $this->createTestOrder('123', 'Test01', static::NOT_RESERVED_ITEM_STATE_EXCEPT_PROCESS_3);
        $this->createTestOrder('456', 'Test02', static::NOT_RESERVED_ITEM_STATE_EXCEPT_PROCESS_3);

        $this->assertTrue(
            $this->getOmsFacade()
                ->sumReservedProductQuantitiesForSku(static::ORDER_ITEM_SKU)
                ->equals(100)
        );

        $order3 = $this->createTestOrder('789', 'Test03', 'new');
        $this->assertTrue(
            $this->getOmsFacade()
                ->sumReservedProductQuantitiesForSku(static::ORDER_ITEM_SKU)
                ->equals(150)
        );

        foreach ($order3->getItems() as $orderItem) {
            $orderItem->setState($this->createOmsOrderItemState(static::NOT_RESERVED_ITEM_STATE_EXCEPT_PROCESS_3))->save();
        }

        $this->assertTrue(
            $this->getOmsFacade()
                ->sumReservedProductQuantitiesForSku(static::ORDER_ITEM_SKU)
                ->equals(100)
        );
    }

    /**
     * @return void
     */
    public function testGetOmsReservedProductQuantityForSkuSumAllItemsInReservedState(): void
    {
        $this->createTestOrder((string)rand(), 'Test01', static::NOT_RESERVED_ITEM_STATE_EXCEPT_PROCESS_3);

        $storeTransfer = (new StoreTransfer())->setName(static::STORE_NAME_DE);
        $reservationQuantity = $this->getOmsFacade()->getOmsReservedProductQuantityForSku(static::ORDER_ITEM_SKU, $storeTransfer);

        $this->assertTrue($reservationQuantity->equals(50));
    }

    /**
     * @param string $orderReference
     * @param string $processName
     * @param string $stateName
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    protected function createTestOrder(string $orderReference, string $processName, string $stateName): SpySalesOrder
    {
        $salesOrderAddressEntity = $this->createSalesOrderAddress();
        $salesOrderEntity = $this->createSalesOrder($salesOrderAddressEntity, $orderReference);
        $omsStateEntity = $this->createOmsOrderItemState($stateName);
        $orderProcess = $this->createOmsOrderProcess($processName);

        for ($i = 0; $i < 5; $i++) {
            $this->createSalesOrderItem($salesOrderEntity, $orderProcess, $omsStateEntity);
        }

        $this->updateReservation($salesOrderEntity->getItems()->getFirst()->getSku(), new Decimal(50));

        return $salesOrderEntity;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     * @param \Orm\Zed\Oms\Persistence\SpyOmsOrderProcess $orderProcess
     * @param \Orm\Zed\Oms\Persistence\SpyOmsOrderItemState $omsStateEntity
     *
     * @return void
     */
    protected function createSalesOrderItem(
        SpySalesOrder $salesOrderEntity,
        SpyOmsOrderProcess $orderProcess,
        SpyOmsOrderItemState $omsStateEntity
    ): void {
        $salesOrderItem = new SpySalesOrderItem();
        $salesOrderItem->setGrossPrice(150);
        $salesOrderItem->setQuantity(10);
        $salesOrderItem->setSku(static::ORDER_ITEM_SKU);
        $salesOrderItem->setName('testOms');
        $salesOrderItem->setTaxRate(12);
        $salesOrderItem->setFkOmsOrderItemState($omsStateEntity->getIdOmsOrderItemState());
        $salesOrderItem->setFkOmsOrderProcess($orderProcess->getIdOmsOrderProcess());
        $salesOrderItem->setFkSalesOrder($salesOrderEntity->getIdSalesOrder());
        $salesOrderItem->save();
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderAddress
     */
    protected function createSalesOrderAddress(): SpySalesOrderAddress
    {
        $salesOrderAddressEntity = new SpySalesOrderAddress();
        $salesOrderAddressEntity->setAddress1(1);
        $salesOrderAddressEntity->setAddress2(2);
        $salesOrderAddressEntity->setSalutation('Mr');
        $salesOrderAddressEntity->setCellPhone('123456789');
        $salesOrderAddressEntity->setCity('City');
        $salesOrderAddressEntity->setComment('comment');
        $salesOrderAddressEntity->setDescription('describtion');
        $salesOrderAddressEntity->setCompany('company');
        $salesOrderAddressEntity->setFirstName('First name');
        $salesOrderAddressEntity->setLastName('Last Name');
        $salesOrderAddressEntity->setFkCountry(1);
        $salesOrderAddressEntity->setEmail('email');
        $salesOrderAddressEntity->setZipCode(10405);
        $salesOrderAddressEntity->save();

        return $salesOrderAddressEntity;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderAddress $salesOrderAddressEntity
     * @param string $orderReference
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    protected function createSalesOrder(SpySalesOrderAddress $salesOrderAddressEntity, string $orderReference): SpySalesOrder
    {
        $salesOrderEntity = new SpySalesOrder();
        $salesOrderEntity->setBillingAddress($salesOrderAddressEntity);
        $salesOrderEntity->setShippingAddress(clone $salesOrderAddressEntity);
        $salesOrderEntity->setOrderReference($orderReference);
        $salesOrderEntity->setStore(static::STORE_NAME_DE);
        $salesOrderEntity->save();

        return $salesOrderEntity;
    }

    /**
     * @param string $stateName
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsOrderItemState
     */
    protected function createOmsOrderItemState(string $stateName): SpyOmsOrderItemState
    {
        $omsStateEntity = SpyOmsOrderItemStateQuery::create()
            ->filterByName($stateName)
            ->findOneOrCreate();

        $omsStateEntity->save();

        return $omsStateEntity;
    }

    /**
     * @param string $processName
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsOrderProcess
     */
    protected function createOmsOrderProcess(string $processName): SpyOmsOrderProcess
    {
        $orderProcessEntity = (new SpyOmsOrderProcessQuery())
            ->filterByName($processName)
            ->findOneOrCreate();

        $orderProcessEntity->save();

        return $orderProcessEntity;
    }

    /**
     * @param string $sku
     * @param \Spryker\DecimalObject\Decimal $quantity
     *
     * @return void
     */
    protected function updateReservation(string $sku, Decimal $quantity): void
    {
        $spyOmsReservationEntity = SpyOmsProductReservationQuery::create()
            ->filterBySku($sku)
            ->findOneOrCreate();

        $storeTransfer = $this->getStoreFacade()->getStoreByName(static::STORE_NAME_DE);

        $spyOmsReservationEntity->setFkStore($storeTransfer->getIdStore());
        $spyOmsReservationEntity->setReservationQuantity($quantity);
        $spyOmsReservationEntity->save();
    }

    /**
     * @return \Spryker\Zed\Kernel\Business\AbstractFacade|\Spryker\Zed\Oms\Business\OmsFacadeInterface
     */
    protected function getOmsFacade(): OmsFacadeInterface
    {
        return $this->tester->getFacade();
    }

    /**
     * @return \Spryker\Zed\Store\Business\StoreFacadeInterface
     */
    protected function getStoreFacade(): StoreFacadeInterface
    {
        return $this->tester->getLocator()->store()->facade();
    }
}
