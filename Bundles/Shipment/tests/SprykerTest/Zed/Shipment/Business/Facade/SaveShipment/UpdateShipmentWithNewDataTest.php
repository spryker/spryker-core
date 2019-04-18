<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Shipment\tests\SprykerTest\Zed\Shipment\Business\Facade\SaveShipment;

use Codeception\TestCase\Test;
use Generated\Shared\DataBuilder\AddressBuilder;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\ShipmentBuilder;
use Generated\Shared\DataBuilder\ShipmentMethodBuilder;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Orm\Zed\Sales\Persistence\SpySalesShipmentQuery;
use Spryker\Shared\Price\PriceConfig;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Shipment
 * @group Business
 * @group Facade
 * @group SaveShipment
 * @group UpdateShipmentWithNewDataTest
 * Add your own group annotations below this line
 */
class UpdateShipmentWithNewDataTest extends Test
{
    protected const TEST_STATE_MACHINE_PROCESS_NAME = 'TestPayment01';

    /**
     * @var \SprykerTest\Zed\Shipment\ShipmentBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([static::TEST_STATE_MACHINE_PROCESS_NAME]);
    }

    /**
     * @dataProvider createNewShipmentForOrderItemDataProvider
     *
     * @param QuoteTransfer $quoteTransfer
     * @param ShipmentTransfer $shipmentTransfer
     * @param ItemTransfer $itemTransfer
     *
     * @return void
     */
    public function testCreateNewShipmentForOrderItem(
        QuoteTransfer $quoteTransfer,
        ShipmentTransfer $shipmentTransfer,
        ItemTransfer $itemTransfer
    ): void {
        // Arrange
        $saveOrderTransfer = $this->tester->haveOrderUsingPreparedQuoteTransfer($quoteTransfer, static::TEST_STATE_MACHINE_PROCESS_NAME);

        $shipmentTransfer->getMethod()->setIdShipmentMethod(
            $this->tester->haveShipmentMethod($shipmentTransfer->getMethod()->toArray())->getIdShipmentMethod()
        );
        $shipmentGroupTransfer = (new ShipmentGroupTransfer())->setShipment($shipmentTransfer);
        $shipmentGroupTransfer->addItem($itemTransfer);

        $orderTransfer = $this->tester->getLocator()->sales()->facade()->getOrderByIdSalesOrder($saveOrderTransfer->getIdSalesOrder());
        $orderTransfer->setItems($saveOrderTransfer->getOrderItems());

        // Act
        $shipmentGroupResponseTransfer = $this->tester->getFacade()->saveShipment($shipmentGroupTransfer, $orderTransfer);

        // Assert
        $this->assertTrue($shipmentGroupResponseTransfer->getIsSuccessful(), 'Saving a new shipment should have been successful.');

        $shipmentTransfer = $shipmentGroupResponseTransfer->getShipmentGroup()->getShipment();
        $this->assertNotNull($shipmentTransfer->getIdSalesShipment(), 'Shipment should have been created.');

        $itemEntities = SpySalesOrderItemQuery::create()->findByFkSalesShipment($shipmentTransfer->getIdSalesShipment());
        $this->assertCount(1, $itemEntities, 'Shipment should have been assigned for one order item');
    }

    /**
     * @return array
     */
    public function createNewShipmentForOrderItemDataProvider(): array
    {
        return [
            'update shipment with new shipment with new address' => $this->getDataWithNewShippingAddress(),
            'move one item to new shipment with new shipment method' => $this->getDataWithNewShipmentMethod(),
            'move one item to new shipment with new delivery date' => $this->getDataWithNewDeliveryDate(),
        ];
    }

    /**
     * @return array
     */
    protected function getDataWithNewShippingAddress(): array
    {
        $quoteTransfer = $this->createQuoteTransfer();
        $itemTransfer = $quoteTransfer->getItems()[0];

        $newShipmentTransfer = clone $itemTransfer
            ->getShipment()
            ->setShippingAddress(
                (new AddressBuilder())->build()
            );

        return [$quoteTransfer, $newShipmentTransfer, $itemTransfer];
    }

    /**
     * @return array
     */
    protected function getDataWithNewShipmentMethod(): array
    {
        $quoteTransfer = $this->createQuoteTransfer();
        $itemTransfer = $quoteTransfer->getItems()[0];

        $newShipmentTransfer = clone $itemTransfer
            ->getShipment()
            ->setMethod(
                (new ShipmentMethodBuilder())->build()
            );

        return [$quoteTransfer, $newShipmentTransfer, $itemTransfer];
    }

    /**
     * @return array
     */
    protected function getDataWithNewDeliveryDate(): array
    {
        $quoteTransfer = $this->createQuoteTransfer();
        $itemTransfer = $quoteTransfer->getItems()[0];

        $newShipmentTransfer = clone $itemTransfer
            ->getShipment()
            ->setRequestedDeliveryDate(
                (new \DateTime())->getTimestamp()
            );

        return [$quoteTransfer, $newShipmentTransfer, $itemTransfer];
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteTransfer(): QuoteTransfer
    {
        return (new QuoteBuilder([
            QuoteTransfer::PRICE_MODE => PriceConfig::PRICE_MODE_NET
        ]))
            ->withItem(
                (new ItemBuilder())
                    ->withShipment(
                        (new ShipmentBuilder())
                            ->withShippingAddress()
                            ->withMethod()
                    )
            )
            ->withAnotherItem(
                (new ItemBuilder())
                    ->withShipment(
                        (new ShipmentBuilder())
                            ->withShippingAddress()
                            ->withMethod()
                    )
            )
            ->withBillingAddress()
            ->withCustomer()
            ->withTotals()
            ->withCurrency()
            ->build();
    }

    /**
     * @return void
     */
    public function testCreateShipmentWithNewShippingAddressShouldCreateNewSalesOrderAddress(): void
    {
        // Arrange
        $quoteTransfer = $this->createQuoteTransfer();
        $saveOrderTransfer = $this->tester->haveOrderUsingPreparedQuoteTransfer($quoteTransfer, static::TEST_STATE_MACHINE_PROCESS_NAME);

        $itemTransfer = $quoteTransfer->getItems()[0];
        $oldIdSalesOrderAddress = $itemTransfer->getShipment()->getShippingAddress()->getIdSalesOrderAddress();

        $shipmentTransfer = clone $itemTransfer
            ->getShipment()
            ->setMethod($this->tester->haveShipmentMethod($itemTransfer->getShipment()->getMethod()->toArray()))
            ->setShippingAddress(
                (new AddressBuilder())->build()
            );

        $shipmentGroupTransfer = (new ShipmentGroupTransfer())->setShipment($shipmentTransfer);

        $orderTransfer = $this->tester->getLocator()->sales()->facade()->getOrderByIdSalesOrder($saveOrderTransfer->getIdSalesOrder());
        $orderTransfer->setItems($saveOrderTransfer->getOrderItems());

        // Act
        $shipmentGroupResponseTransfer = $this->tester->getFacade()->saveShipment($shipmentGroupTransfer, $orderTransfer);

        // Assert
        $shipmentEntity = SpySalesShipmentQuery::create()->findOneByIdSalesShipment(
            $shipmentGroupResponseTransfer->getShipmentGroup()->getShipment()->getIdSalesShipment()
        );

        // Assert
        $this->assertTrue($shipmentGroupResponseTransfer->getIsSuccessful(), 'Saving a new shipment should have been successful.');
        $this->assertNotNull($shipmentEntity->getFkSalesOrderAddress(), 'New sales shipment should have a sales order address.');
        $this->assertNotEquals($oldIdSalesOrderAddress, $shipmentEntity->getFkSalesOrderAddress(), 'New sales shipment should have been a new sales order address assigned.');

        // todo move to expense test
    }

    /**
     * @return void
     */
    public function testCreateShipmentWithNewShippingAddressShouldCreateNewSalesExpense(): void
    {
        // Arrange
        $quoteTransfer = $this->createQuoteTransfer();
        $saveOrderTransfer = $this->tester->haveOrderUsingPreparedQuoteTransfer($quoteTransfer, static::TEST_STATE_MACHINE_PROCESS_NAME);

        $itemTransfer = $quoteTransfer->getItems()[0];

        $shipmentTransfer = (new ShipmentBuilder())
            ->withShippingAddress()
            ->withMethod($this->tester->haveShipmentMethod()->toArray())
            ->build();

        $shipmentGroupTransfer = (new ShipmentGroupTransfer())->setShipment($shipmentTransfer);
        $shipmentGroupTransfer->addItem($itemTransfer);

        $orderTransfer = $this->tester->getLocator()->sales()->facade()->getOrderByIdSalesOrder($saveOrderTransfer->getIdSalesOrder());
        $orderTransfer->setItems($saveOrderTransfer->getOrderItems());

        // Act
        $shipmentGroupResponseTransfer = $this->tester->getFacade()->saveShipment($shipmentGroupTransfer, $orderTransfer);

        // Assert
        $shipmentEntity = SpySalesShipmentQuery::create()->findOneByIdSalesShipment(
            $shipmentGroupResponseTransfer->getShipmentGroup()->getShipment()->getIdSalesShipment()
        );

        // Assert
        $this->assertTrue($shipmentGroupResponseTransfer->getIsSuccessful(), 'Saving a new shipment should have been successful.');
        $this->assertNotNull($shipmentEntity->getFkSalesExpense(), 'ew sales shipment should have been a new sales expense assigned.');
        $this->assertEquals(0, $shipmentEntity->getExpense()->getPrice(), 'New shipments must have 0 price as expense.');
    }
}
