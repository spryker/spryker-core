<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Sales\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\AddressBuilder;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\OrderBuilder;
use Generated\Shared\DataBuilder\ShipmentBuilder;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderListTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\StockProductTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Spryker\Zed\Sales\Business\SalesFacadeInterface;
use SprykerTest\Zed\Sales\Helper\BusinessHelper;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Sales
 * @group Business
 * @group Facade
 * @group SalesFacadeTest
 * Add your own group annotations below this line
 */
class SalesFacadeTest extends Unit
{
    protected const DEFAULT_OMS_PROCESS_NAME = 'Test01';
    protected const DEFAULT_ITEM_STATE = 'test';

    protected const ORDER_WRONG_SEARCH_PARAMS = [
        'orderReference' => '123_wrong',
        'customerReference' => 'testing-customer-wrong',
    ];

    /**
     * @var \SprykerTest\Zed\Sales\SalesBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetOrderByIdSalesOrderShouldReturnOrderTransferWithOrderDataAndTotals(): void
    {
        $productTransfer = $this->tester->haveProduct();
        $this->tester->haveProductInStock([StockProductTransfer::SKU => $productTransfer->getSku()]);

        $salesOrderEntity = $this->tester->create();

        $salesFacade = $this->createSalesFacade();

        $orderTransfer = $salesFacade->getOrderByIdSalesOrder($salesOrderEntity->getIdSalesOrder());

        $this->assertInstanceOf(OrderTransfer::class, $orderTransfer);
        $this->assertInstanceOf(TotalsTransfer::class, $orderTransfer->getTotals());
        $this->assertCount(2, $orderTransfer->getItems());

        $itemTransfer = $orderTransfer->getItems()[0];
        $this->assertEquals(static::DEFAULT_ITEM_STATE, $itemTransfer->getState()->getName());
        $this->assertEquals(static::DEFAULT_OMS_PROCESS_NAME, $itemTransfer->getProcess());

        $itemTransfer = $orderTransfer->getItems()[1];
        $this->assertEquals(static::DEFAULT_ITEM_STATE, $itemTransfer->getState()->getName());
        $this->assertEquals(static::DEFAULT_OMS_PROCESS_NAME, $itemTransfer->getProcess());

        $this->assertInstanceOf(AddressTransfer::class, $orderTransfer->getBillingAddress());
        $this->assertInstanceOf(AddressTransfer::class, $orderTransfer->getShippingAddress());
        $this->assertCount(1, $orderTransfer->getExpenses());

        $this->assertSame(1, $orderTransfer->getTotalOrderCount());
    }

    /**
     * @return void
     */
    public function testGetOrderByIdSalesOrderWhenGuestCustomerShouldNotCountOrders(): void
    {
        $productTransfer = $this->tester->haveProduct();
        $this->tester->haveProductInStock([StockProductTransfer::SKU => $productTransfer->getSku()]);

        $salesOrderEntity = $this->tester->create();

        $salesOrderEntity->setCustomerReference(null);
        $salesOrderEntity->save();

        $salesFacade = $this->createSalesFacade();

        $orderTransfer = $salesFacade->getOrderByIdSalesOrder($salesOrderEntity->getIdSalesOrder());

        $this->assertSame(0, $orderTransfer->getTotalOrderCount());
    }

    /**
     * @return void
     */
    public function testCustomerOrderShouldReturnListOfCustomerPlacedOrders(): void
    {
        $salesOrderEntity = $this->tester->create();

        $this->tester->configureTestStateMachine([BusinessHelper::DEFAULT_OMS_PROCESS_NAME]);

        $salesFacade = $this->createSalesFacade();

        $orderListTransfer = new OrderListTransfer();

        $orderListTransfer = $salesFacade->getCustomerOrders($orderListTransfer, $salesOrderEntity->getFkCustomer());

        $this->assertInstanceOf(OrderListTransfer::class, $orderListTransfer);
    }

    /**
     * @return void
     */
    public function testGetCustomerOrderByOrderReference(): void
    {
        //Arrange
        $orderEntity = $this->tester->haveSalesOrderEntity();
        $salesFacade = $this->createSalesFacade();

        //Act
        $order = $salesFacade->getCustomerOrderByOrderReference(
            $this->createOrderTransferWithParams([
                OrderTransfer::ORDER_REFERENCE => $orderEntity->getOrderReference(),
                OrderTransfer::CUSTOMER_REFERENCE => $orderEntity->getCustomerReference(),
            ])
        );

        //Assert
        $this->assertNotNull($order);
        $this->assertSame($orderEntity->getIdSalesOrder(), $order->getIdSalesOrder());
    }

    /**
     * @return void
     */
    public function testGetCustomerOrderByNonExistingOrderReference(): void
    {
        $salesFacade = $this->createSalesFacade();

        $order = $salesFacade->getCustomerOrderByOrderReference(
            $this->createOrderTransferWithParams(static::ORDER_WRONG_SEARCH_PARAMS)
        );

        $this->assertNull($order->getIdSalesOrder());
    }

    /**
     * @dataProvider getUniqueItemsFromOrderDataProvider
     *
     * @param \Generated\Shared\Transfer\ItemTransfer[] $orderItems
     * @param int $expectedItemsCount
     *
     * @return void
     */
    public function testGetUniqueItemsFromOrder(array $orderItems, int $expectedItemsCount): void
    {
        //Arrange
        $salesOrderEntity = $this->tester->haveSalesOrderEntity($orderItems);
        $salesFacade = $this->createSalesFacade();
        $orderTransfer = $salesFacade->getOrderByIdSalesOrder($salesOrderEntity->getIdSalesOrder());

        //Act
        $actualUniqueItems = $salesFacade->getUniqueItemsFromOrder($orderTransfer);

        //Assert
        $this->assertCount($expectedItemsCount, $actualUniqueItems);
    }

    /**
     * @return array
     */
    public function getUniqueItemsFromOrderDataProvider(): array
    {
        return [
            'order has 1 ordinary item with quantity 2; expected: 1 unique item' => $this->getDataWith1OrdinaryItemWithQuantity2(),
            'order has 1 product bundle item with quantity 2; expected: 1 unique item' => $this->getDataWith1ProductBundleItemWithQuantity2(),
            'order has 1 ordinary item with quantity 2 and 1 product bundle item with quantity 2; expected: 2 unique items' => $this->getDataWith1OrdinaryItemWithQuantity2And1ProductBundleItemWithQuantity2(),
        ];
    }

    /**
     * @return array
     */
    protected function getDataWith1OrdinaryItemWithQuantity2(): array
    {
        return [[$this->getNewItemTransfer()], 1];
    }

    /**
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function getDataWith1ProductBundleItemWithQuantity2(): array
    {
        $bundleItemTransfer1 = $this->getNewItemTransfer([ItemTransfer::BUNDLE_ITEM_IDENTIFIER => 'bundle item 1']);
        $bundleItemTransfer2 = $this->getNewItemTransfer([
            ItemTransfer::RELATED_BUNDLE_ITEM_IDENTIFIER => $bundleItemTransfer1->getBundleItemIdentifier(),
            ItemTransfer::BUNDLE_ITEM_IDENTIFIER => 'bundle item 2',
        ]);

        return [[$bundleItemTransfer1, $bundleItemTransfer2], 1];
    }

    /**
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function getDataWith1OrdinaryItemWithQuantity2And1ProductBundleItemWithQuantity2(): array
    {
        $bundleItemTransfer1 = $this->getNewItemTransfer([ItemTransfer::BUNDLE_ITEM_IDENTIFIER => 'bundle item 1']);
        $bundleItemTransfer2 = $this->getNewItemTransfer([
            ItemTransfer::RELATED_BUNDLE_ITEM_IDENTIFIER => $bundleItemTransfer1->getBundleItemIdentifier(),
            ItemTransfer::BUNDLE_ITEM_IDENTIFIER => 'bundle item 2',
        ]);

        return [[$this->getNewItemTransfer(), $bundleItemTransfer1, $bundleItemTransfer2], 2];
    }

    /**
     * @param array|null $itemAttributes
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function getNewItemTransfer(array $itemAttributes = []): ItemTransfer
    {
        $addressBuilder = (new AddressBuilder());
        $shipmentTransfer = (new ShipmentBuilder())
            ->withShippingAddress($addressBuilder)
            ->withMethod()
            ->build();

        $itemTransfer = (new ItemBuilder($itemAttributes))->build();
        $itemTransfer->setShipment($shipmentTransfer);

        return $itemTransfer;
    }

    /**
     * @return \Spryker\Zed\Sales\Business\SalesFacadeInterface
     */
    protected function createSalesFacade(): SalesFacadeInterface
    {
        return $this->tester->getLocator()->sales()->facade();
    }

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function createOrderTransferWithParams(array $data): OrderTransfer
    {
        return (new OrderBuilder())->build()->fromArray($data);
    }
}
