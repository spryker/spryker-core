<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Shipment\Business\Facade\SaveShipment;

use Codeception\TestCase\Test;
use DateTime;
use Generated\Shared\DataBuilder\AddressBuilder;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\ShipmentBuilder;
use Generated\Shared\DataBuilder\ShipmentMethodBuilder;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Orm\Zed\Sales\Persistence\SpySalesShipmentQuery;
use Spryker\Shared\Price\PriceConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Shipment
 * @group Business
 * @group Facade
 * @group SaveShipment
 * @group CreateShipmentWithNewDataTest
 * Add your own group annotations below this line
 */
class CreateShipmentWithNewDataTest extends Test
{
    /**
     * @var \SprykerTest\Zed\Shipment\ShipmentBusinessTester
     */
    protected $tester;

    /**
     * @dataProvider createNewShipmentForOrderItemDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    public function testCreateNewShipmentForOrderItem(
        QuoteTransfer $quoteTransfer,
        ShipmentTransfer $shipmentTransfer,
        ItemTransfer $itemTransfer
    ): void {
        // Arrange
        $saveOrderTransfer = $this->tester->createOrderWithMultiShipment($quoteTransfer);

        $shipmentTransfer->getMethod()->setIdShipmentMethod(
            $this->tester->haveShipmentMethod($shipmentTransfer->getMethod()->toArray())->getIdShipmentMethod()
        );
        $shipmentGroupTransfer = (new ShipmentGroupTransfer())->setShipment($shipmentTransfer);
        $shipmentGroupTransfer->addItem($itemTransfer);

        $orderTransfer = $this->tester->getOrderTransferByIdSalesOrder($saveOrderTransfer->getIdSalesOrder());

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
            'move one item to new shipment with new address' => $this->getDataWithNewShippingAddress(),
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

        $actualShipmentTransfer = clone $itemTransfer->getShipment();
        $actualShipmentTransfer->setShippingAddress(
            (new AddressBuilder())->build()
        );

        return [$quoteTransfer, $actualShipmentTransfer, $itemTransfer];
    }

    /**
     * @return array
     */
    protected function getDataWithNewShipmentMethod(): array
    {
        $quoteTransfer = $this->createQuoteTransfer();
        $itemTransfer = $quoteTransfer->getItems()[0];

        $actualShipmentTransfer = clone $itemTransfer
            ->getShipment()
            ->setMethod(
                (new ShipmentMethodBuilder())->seed([
                    ShipmentMethodTransfer::STORE_RELATION => new StoreRelationTransfer(),
                ])->build()
            );

        return [$quoteTransfer, $actualShipmentTransfer, $itemTransfer];
    }

    /**
     * @return array
     */
    protected function getDataWithNewDeliveryDate(): array
    {
        $quoteTransfer = $this->createQuoteTransfer();
        $itemTransfer = $quoteTransfer->getItems()[0];

        $actualShipmentTransfer = clone $itemTransfer
            ->getShipment()
            ->setRequestedDeliveryDate(
                (new DateTime())->getTimestamp()
            );

        return [$quoteTransfer, $actualShipmentTransfer, $itemTransfer];
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteTransfer(): QuoteTransfer
    {
        return (new QuoteBuilder([
            QuoteTransfer::PRICE_MODE => PriceConfig::PRICE_MODE_NET,
        ]))
            ->withItem(
                (new ItemBuilder())->seed([
                    ItemTransfer::UNIT_PRICE => 500,
                ])
                    ->withShipment(
                        (new ShipmentBuilder())
                            ->withShippingAddress()
                            ->withMethod([
                                ShipmentMethodTransfer::STORE_RELATION => new StoreRelationTransfer(),
                            ])
                    )
            )
            ->withAnotherItem(
                (new ItemBuilder())->seed([
                    ItemTransfer::UNIT_PRICE => 500,
                ])
                    ->withShipment(
                        (new ShipmentBuilder())
                            ->withShippingAddress()
                            ->withMethod([
                                ShipmentMethodTransfer::STORE_RELATION => new StoreRelationTransfer(),
                            ])
                    )
            )
            ->withBillingAddress()
            ->withCustomer()
            ->withTotals()
            ->withCurrency()
            ->build();
    }

    /**
     * @dataProvider createShipmentWithNewShippingAddressDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    public function testCreateShipmentWithNewShippingAddressShouldCreateNewSalesOrderAddress(
        QuoteTransfer $quoteTransfer,
        ShipmentTransfer $shipmentTransfer,
        ItemTransfer $itemTransfer
    ): void {
        // Arrange
        $saveOrderTransfer = $this->tester->createOrderWithMultiShipment($quoteTransfer);

        $itemTransfer = $saveOrderTransfer->getOrderItems()[0];
        $expectedIdSalesOrderAddress = $itemTransfer->getShipment()->getShippingAddress()->getIdSalesOrderAddress();

        $shipmentTransfer->setMethod($this->tester->haveShipmentMethod());

        $shipmentGroupTransfer = new ShipmentGroupTransfer();
        $shipmentGroupTransfer->setShipment($shipmentTransfer);
        $shipmentGroupTransfer->addItem($itemTransfer);

        $orderTransfer = $this->tester->getOrderTransferByIdSalesOrder($saveOrderTransfer->getIdSalesOrder());

        // Act
        $shipmentGroupResponseTransfer = $this->tester->getFacade()->saveShipment($shipmentGroupTransfer, $orderTransfer);

        // Assert
        $shipmentEntity = SpySalesShipmentQuery::create()->findOneByIdSalesShipment(
            $shipmentGroupResponseTransfer->getShipmentGroup()->getShipment()->getIdSalesShipment()
        );

        $this->assertTrue($shipmentGroupResponseTransfer->getIsSuccessful(), 'Saving a new shipment should have been successful.');
        $this->assertNotNull($shipmentEntity->getFkSalesOrderAddress(), 'New sales shipment should have a sales order address.');
        $this->assertNotEquals($expectedIdSalesOrderAddress, $shipmentEntity->getFkSalesOrderAddress(), 'New sales shipment should have been a new sales order address assigned.');
    }

    /**
     * @return array
     */
    public function createShipmentWithNewShippingAddressDataProvider(): array
    {
        return [
            'save shipment with new shipment address' => $this->getDataWithNewShippingAddress(),
        ];
    }

    /**
     * @dataProvider createShipmentWithNewShippingAddressDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    public function testCreateShipmentWithNewShippingAddressShouldCreateNewSalesExpense(
        QuoteTransfer $quoteTransfer,
        ShipmentTransfer $shipmentTransfer,
        ItemTransfer $itemTransfer
    ): void {
        // Arrange
        $saveOrderTransfer = $this->tester->createOrderWithMultiShipment($quoteTransfer);
        $orderTransfer = $this->tester->getOrderTransferByIdSalesOrder($saveOrderTransfer->getIdSalesOrder());

        $itemTransfer = $orderTransfer->getItems()[0];

        $shipmentTransfer->setMethod($this->tester->haveShipmentMethod());

        $shipmentGroupTransfer = new ShipmentGroupTransfer();
        $shipmentGroupTransfer->setShipment($shipmentTransfer);
        $shipmentGroupTransfer->addItem($itemTransfer);

        // Act
        $shipmentGroupResponseTransfer = $this->tester->getFacade()->saveShipment($shipmentGroupTransfer, $orderTransfer);

        // Assert
        $shipmentEntity = SpySalesShipmentQuery::create()->findOneByIdSalesShipment(
            $shipmentGroupResponseTransfer->getShipmentGroup()->getShipment()->getIdSalesShipment()
        );

        $this->assertTrue($shipmentGroupResponseTransfer->getIsSuccessful(), 'Saving a new shipment should have been successful.');
        $this->assertNotNull($shipmentEntity->getFkSalesExpense(), 'New sales shipment should have been a new sales expense assigned.');
        $this->assertEquals(0, $shipmentEntity->getExpense()->getPrice(), 'New shipments must have 0 price as expense.');
    }

    /**
     * @dataProvider createShipmentWithNewShippingAddressDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    public function testCreateShipmentForAllItemsShouldKeepOldShipmentEmpty(
        QuoteTransfer $quoteTransfer,
        ShipmentTransfer $shipmentTransfer,
        ItemTransfer $itemTransfer
    ): void {
        // Arrange
        $saveOrderTransfer = $this->tester->createOrderWithMultiShipment($quoteTransfer);
        $oldShipmentTransfer = $saveOrderTransfer->getOrderItems()[0]->getShipment();
        $shipmentTransfer->setMethod($this->tester->haveShipmentMethod());

        $shipmentGroupTransfer = (new ShipmentGroupTransfer())
            ->setShipment($shipmentTransfer);

        foreach ($saveOrderTransfer->getOrderItems() as $itemTransfer) {
            $shipmentGroupTransfer->addItem($itemTransfer);
        }

        $orderTransfer = $this->tester->getOrderTransferByIdSalesOrder($saveOrderTransfer->getIdSalesOrder());

        // Act
        $shipmentGroupResponseTransfer = $this->tester->getFacade()->saveShipment($shipmentGroupTransfer, $orderTransfer);

        // Assert
        $this->assertTrue($shipmentGroupResponseTransfer->getIsSuccessful(), 'New shipment should have been created successful.');

        $shipmentEntity = SpySalesShipmentQuery::create()->findOneByIdSalesShipment($oldShipmentTransfer->getIdSalesShipment());
        $this->assertNotNull($shipmentEntity, 'New shipment creation should keep old shipment');

        $itemEntities = SpySalesOrderItemQuery::create()->findByFkSalesShipment($oldShipmentTransfer->getIdSalesShipment());
        $this->assertCount(0, $itemEntities, 'Old shipment should not have any assigned items.');
    }
}
