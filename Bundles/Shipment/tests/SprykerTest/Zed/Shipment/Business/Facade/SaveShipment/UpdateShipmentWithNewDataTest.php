<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Shipment\tests\SprykerTest\Zed\Shipment\Business\Facade\SaveShipment;

use Codeception\TestCase\Test;
use DateTime;
use Generated\Shared\DataBuilder\AddressBuilder;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\ShipmentBuilder;
use Generated\Shared\DataBuilder\ShipmentMethodBuilder;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Orm\Zed\Sales\Persistence\SpySalesShipmentQuery;
use Spryker\Shared\Price\PriceConfig;

/**
 * Auto-generated group annotations
 *
 * @group Shipment
 * @group tests
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
    /**
     * @var \SprykerTest\Zed\Shipment\ShipmentBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testUpdateShipmentWithNewShippingAddress(): void
    {
        // Arrange
        $quoteTransfer = $this->createQuoteTransfer();
        $saveOrderTransfer = $this->tester->createOrderWithMultiShipment($quoteTransfer);

        $shipmentTransfer = $saveOrderTransfer->getOrderItems()[0]->getShipment();
        $shipmentTransfer->setMethod($this->tester->haveShipmentMethod());

        $expectedIdSalesOrderAddress = $shipmentTransfer->getShippingAddress()->getIdSalesOrderAddress();
        $shipmentTransfer->setShippingAddress((new AddressBuilder())->build());

        $shipmentGroupTransfer = (new ShipmentGroupTransfer())->setShipment($shipmentTransfer);

        $orderTransfer = $this->tester->getOrderTransferByIdSalesOrder($saveOrderTransfer->getIdSalesOrder());

        // Act
        $shipmentGroupResponseTransfer = $this->tester->getFacade()->saveShipment($shipmentGroupTransfer, $orderTransfer);

        // Assert
        $shipmentEntity = SpySalesShipmentQuery::create()->findOneByIdSalesShipment(
            $shipmentGroupResponseTransfer->getShipmentGroup()->getShipment()->getIdSalesShipment()
        );

        $this->assertTrue($shipmentGroupResponseTransfer->getIsSuccessful(), 'Saving a shipment should have been successful.');
        $this->assertEquals($shipmentTransfer->getIdSalesShipment(), $shipmentEntity->getIdSalesShipment(), 'The shipment should have been updated.');
        $this->assertNotNull($shipmentEntity->getFkSalesOrderAddress(), 'The sales shipment should have a sales order address.');
        $this->assertNotEquals($expectedIdSalesOrderAddress, $shipmentEntity->getFkSalesOrderAddress(), 'The sales shipment should have been a new sales order address assigned.');
    }

    /**
     * @return void
     */
    public function testUpdateShipmentWithNewShipmentMethod(): void
    {
        // Arrange
        $quoteTransfer = $this->createQuoteTransfer();
        $saveOrderTransfer = $this->tester->createOrderWithMultiShipment($quoteTransfer);

        $shipmentTransfer = $saveOrderTransfer->getOrderItems()[0]->getShipment();
        $expectedShipmentMethod = $shipmentTransfer->getMethod()->getName();
        $shipmentTransfer->setMethod(
            $this->tester->haveShipmentMethod((new ShipmentMethodBuilder())->build()->toArray())
        );

        $shipmentGroupTransfer = (new ShipmentGroupTransfer())->setShipment($shipmentTransfer);

        $orderTransfer = $this->tester->getOrderTransferByIdSalesOrder($saveOrderTransfer->getIdSalesOrder());

        // Act
        $shipmentGroupResponseTransfer = $this->tester->getFacade()->saveShipment($shipmentGroupTransfer, $orderTransfer);

        // Assert
        $shipmentEntity = SpySalesShipmentQuery::create()->findOneByIdSalesShipment(
            $shipmentGroupResponseTransfer->getShipmentGroup()->getShipment()->getIdSalesShipment()
        );

        $this->assertTrue($shipmentGroupResponseTransfer->getIsSuccessful(), 'Saving a shipment should have been successful.');
        $this->assertEquals($shipmentTransfer->getIdSalesShipment(), $shipmentEntity->getIdSalesShipment(), 'The shipment should have been updated.');
        $this->assertNotEquals(
            $expectedShipmentMethod,
            $shipmentEntity->getName(),
            'New shipment method should have been assigned to shipment.'
        );
    }

    /**
     * @return void
     */
    public function testUpdateShipmentWithNewDeliveryDate(): void
    {
        // Arrange
        $quoteTransfer = $this->createQuoteTransfer();
        $saveOrderTransfer = $this->tester->createOrderWithMultiShipment($quoteTransfer);

        $shipmentTransfer = $saveOrderTransfer->getOrderItems()[0]->getShipment();
        $shipmentTransfer->setMethod($this->tester->haveShipmentMethod($shipmentTransfer->getMethod()->toArray()));

        $expectedDeliveryDate = $shipmentTransfer->getRequestedDeliveryDate();

        $actualDeliveryDate = (new DateTime())->getTimestamp();
        $shipmentTransfer->setRequestedDeliveryDate($actualDeliveryDate);

        $shipmentGroupTransfer = (new ShipmentGroupTransfer())->setShipment($shipmentTransfer);

        $orderTransfer = $this->tester->getOrderTransferByIdSalesOrder($saveOrderTransfer->getIdSalesOrder());

        // Act
        $shipmentGroupResponseTransfer = $this->tester->getFacade()->saveShipment($shipmentGroupTransfer, $orderTransfer);

        // Assert
        $shipmentEntity = SpySalesShipmentQuery::create()->findOneByIdSalesShipment(
            $shipmentGroupResponseTransfer->getShipmentGroup()->getShipment()->getIdSalesShipment()
        );

        $this->assertTrue($shipmentGroupResponseTransfer->getIsSuccessful(), 'Saving a shipment should have been successful.');
        $this->assertEquals($shipmentTransfer->getIdSalesShipment(), $shipmentEntity->getIdSalesShipment(), 'The shipment should have been updated.');
        $this->assertNotEquals($expectedDeliveryDate, $shipmentEntity->getRequestedDeliveryDate(), 'The shipment should have been updated with new delivery date.');
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
}
