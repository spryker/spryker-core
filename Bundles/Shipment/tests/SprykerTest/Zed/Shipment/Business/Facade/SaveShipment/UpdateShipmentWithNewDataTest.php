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
     * @return void
     */
    public function testUpdateShipmentWithNewShippingAddress(): void
    {
        // Arrange
        $quoteTransfer = $this->createQuoteTransfer();
        $saveOrderTransfer = $this->tester->haveOrderUsingPreparedQuoteTransfer($quoteTransfer, static::TEST_STATE_MACHINE_PROCESS_NAME);

        $shipmentTransfer = $this->tester->haveShipment(
            $saveOrderTransfer->getIdSalesOrder(),
            $saveOrderTransfer->getOrderItems()[0]->getShipment()->toArray()
        );
        $shipmentTransfer->setMethod($this->tester->haveShipmentMethod($shipmentTransfer->getMethod()->toArray()));

        $oldIdSalesOrderAddress = $shipmentTransfer->getShippingAddress()->getIdSalesOrderAddress();
        $shipmentTransfer->setShippingAddress((new AddressBuilder())->build());

        $shipmentGroupTransfer = (new ShipmentGroupTransfer())->setShipment($shipmentTransfer);

        $orderTransfer = $this->tester->getLocator()->sales()->facade()->getOrderByIdSalesOrder($saveOrderTransfer->getIdSalesOrder());

        // Act
        $shipmentGroupResponseTransfer = $this->tester->getFacade()->saveShipment($shipmentGroupTransfer, $orderTransfer);

        // Assert
        $shipmentEntity = SpySalesShipmentQuery::create()->findOneByIdSalesShipment(
            $shipmentGroupResponseTransfer->getShipmentGroup()->getShipment()->getIdSalesShipment()
        );

        // Assert
        $this->assertTrue($shipmentGroupResponseTransfer->getIsSuccessful(), 'Saving a shipment should have been successful.');
        $this->assertEquals($shipmentTransfer->getIdSalesShipment(), $shipmentEntity->getIdSalesShipment(), 'The shipment should have been updated.');
        $this->assertNotNull($shipmentEntity->getFkSalesOrderAddress(), 'The sales shipment should have a sales order address.');
        $this->assertNotEquals($oldIdSalesOrderAddress, $shipmentEntity->getFkSalesOrderAddress(), 'The sales shipment should have been a new sales order address assigned.');
    }

    /**
     * @return void
     */
    public function testUpdateShipmentWithNewShipmentMethod(): void
    {
        // Arrange
        $quoteTransfer = $this->createQuoteTransfer();
        $saveOrderTransfer = $this->tester->haveOrderUsingPreparedQuoteTransfer($quoteTransfer, static::TEST_STATE_MACHINE_PROCESS_NAME);

        $shipmentTransfer = $this->tester->haveShipment(
            $saveOrderTransfer->getIdSalesOrder(),
            $saveOrderTransfer->getOrderItems()[0]->getShipment()->toArray()
        );
        $oldIdShipmentMethod = $shipmentTransfer->getMethod()->getIdShipmentMethod();

        $newShipmentMethod = $this->tester->haveShipmentMethod((new ShipmentMethodBuilder())->build()->toArray());
        $shipmentTransfer->setMethod($newShipmentMethod);

        $shipmentGroupTransfer = (new ShipmentGroupTransfer())->setShipment($shipmentTransfer);

        $orderTransfer = $this->tester->getLocator()->sales()->facade()->getOrderByIdSalesOrder($saveOrderTransfer->getIdSalesOrder());

        // Act
        $shipmentGroupResponseTransfer = $this->tester->getFacade()->saveShipment($shipmentGroupTransfer, $orderTransfer);

        // Assert
        $shipmentEntity = SpySalesShipmentQuery::create()->findOneByIdSalesShipment(
            $shipmentGroupResponseTransfer->getShipmentGroup()->getShipment()->getIdSalesShipment()
        );

        $this->assertTrue($shipmentGroupResponseTransfer->getIsSuccessful(), 'Saving a shipment should have been successful.');
        $this->assertEquals($shipmentTransfer->getIdSalesShipment(), $shipmentEntity->getIdSalesShipment(), 'The shipment should have been updated.');
        $this->assertNotEquals(
            $oldIdShipmentMethod,
            $shipmentGroupResponseTransfer->getShipmentGroup()->getShipment()->getMethod()->getIdShipmentMethod(),
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
        $saveOrderTransfer = $this->tester->haveOrderUsingPreparedQuoteTransfer($quoteTransfer, static::TEST_STATE_MACHINE_PROCESS_NAME);

        $shipmentTransfer = $this->tester->haveShipment(
            $saveOrderTransfer->getIdSalesOrder(),
            $saveOrderTransfer->getOrderItems()[0]->getShipment()->toArray()
        );
        $shipmentTransfer->setMethod($this->tester->haveShipmentMethod($shipmentTransfer->getMethod()->toArray()));

        $oldDeliveryDate = $shipmentTransfer->getRequestedDeliveryDate();
        $newDeliveryDate = (new DateTime())->getTimestamp();
        $shipmentTransfer->setRequestedDeliveryDate($newDeliveryDate);

        $shipmentGroupTransfer = (new ShipmentGroupTransfer())->setShipment($shipmentTransfer);

        $orderTransfer = $this->tester->getLocator()->sales()->facade()->getOrderByIdSalesOrder($saveOrderTransfer->getIdSalesOrder());

        // Act
        $shipmentGroupResponseTransfer = $this->tester->getFacade()->saveShipment($shipmentGroupTransfer, $orderTransfer);

        // Assert
        $shipmentEntity = SpySalesShipmentQuery::create()->findOneByIdSalesShipment(
            $shipmentGroupResponseTransfer->getShipmentGroup()->getShipment()->getIdSalesShipment()
        );

        $this->assertTrue($shipmentGroupResponseTransfer->getIsSuccessful(), 'Saving a shipment should have been successful.');
        $this->assertEquals($shipmentTransfer->getIdSalesShipment(), $shipmentEntity->getIdSalesShipment(), 'The shipment should have been updated.');
        $this->assertNotEquals($oldDeliveryDate, $shipmentEntity->getRequestedDeliveryDate(), 'The shipment should have been updated with new delivery date.');
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
