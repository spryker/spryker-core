<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Shipment\Business\Facade\SaveOrderShipment;

use Codeception\TestCase\Test;
use Generated\Shared\DataBuilder\AddressBuilder;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\ShipmentBuilder;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Sales\Persistence\SpySalesShipmentQuery;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Shipment
 * @group Business
 * @group Facade
 * @group SaveOrderShipment
 * @group ShipmentPersistenceTest
 * Add your own group annotations below this line
 */
class ShipmentPersistenceTest extends Test
{
    /**
     * @var \SprykerTest\Zed\Shipment\ShipmentBusinessTester
     */
    protected $tester;

    /**
     * @dataProvider shipmentOrderSaverShouldUseQuoteLevelShipmentDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function testShipmentOrderSaverShouldUseQuoteLevelShipment(
        QuoteTransfer $quoteTransfer
    ): void {
        // Arrange
        $saveOrderTransfer = $this->tester->createOrderWithoutShipment($quoteTransfer);

        $salesShipmentQuery = SpySalesShipmentQuery::create()->filterByFkSalesOrder($saveOrderTransfer->getIdSalesOrder());

        // Act
        $this->tester->getFacade()->saveOrderShipment($quoteTransfer, $saveOrderTransfer);

        // Assert
        $shipmentEntity = $salesShipmentQuery->findOne();

        $this->assertNotNull($shipmentEntity, 'There is no shipment has been saved.');
    }

    /**
     * @dataProvider shipmentOrderSaverShouldUseMultipleShipmentsWithMultipleShipmentsArePersistedDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int $countOfNewShipments
     *
     * @return void
     */
    public function testShipmentOrderSaverShouldUseMultipleShipmentsWithMultipleShipmentsArePersisted(
        QuoteTransfer $quoteTransfer,
        int $countOfNewShipments
    ): void {
        // Arrange
        $saveOrderTransfer = $this->tester->createOrderWithoutShipment($quoteTransfer);

        $salesShipmentQuery = SpySalesShipmentQuery::create()->filterByFkSalesOrder($saveOrderTransfer->getIdSalesOrder());

        // Act
        $this->tester->getFacade()->saveOrderShipment($quoteTransfer, $saveOrderTransfer);

        // Assert
        $this->assertEquals($countOfNewShipments, $salesShipmentQuery->count(), 'Saved order shipments count mismatch!');
    }

    /**
     * @return array
     */
    public function shipmentOrderSaverShouldUseQuoteLevelShipmentDataProvider(): array
    {
        return [
            'any data; expected: shipment in DB' => $this->getDataWithQuoteLevelShipmentToFrance(),
        ];
    }

    /**
     * @return array
     */
    public function shipmentOrderSaverShouldUseMultipleShipmentsWithMultipleShipmentsArePersistedDataProvider(): array
    {
        return [
            'France 1 item; expected: 1 order shipment in DB' => $this->getDataWithMultipleShipmentsAnd1ItemToFrance(),
            'France 2 items, Germany 1 item; expected: 2 order shipments in DB' => $this->getDataWithMultipleShipmentsAnd2ItemsToFranceAnd1ItemToGermany(),
            'France 3 items; expected: 1 order shipments in DB' => $this->getDataWithMultipleShipmentsAnd3ItemsToFrance(),
        ];
    }

    /**
     * @return array
     */
    protected function getDataWithQuoteLevelShipmentToFrance(): array
    {
        $addressBuilder = (new AddressBuilder([AddressTransfer::ISO2_CODE => 'FR']));

        $shipmentBuilder = (new ShipmentBuilder())
            ->withShippingAddress($addressBuilder)
            ->withMethod();

        $quoteTransfer = (new QuoteBuilder())
            ->withShipment($shipmentBuilder)
            ->withItem()
            ->withShippingAddress($addressBuilder)
            ->withBillingAddress()
            ->withTotals()
            ->withCustomer()
            ->withCurrency()
            ->build();

        return [$quoteTransfer];
    }

    /**
     * @return array
     */
    protected function getDataWithMultipleShipmentsAnd1ItemToFrance(): array
    {
        $addressBuilder = (new AddressBuilder([AddressTransfer::ISO2_CODE => 'FR']));

        $shipmentBuilder = (new ShipmentBuilder())
            ->withShippingAddress($addressBuilder)
            ->withMethod();

        $itemBuilder = (new ItemBuilder())
            ->withShipment($shipmentBuilder);

        $quoteTransfer = (new QuoteBuilder())
            ->withItem($itemBuilder)
            ->withBillingAddress()
            ->withTotals()
            ->withCustomer()
            ->withCurrency()
            ->build();

        return [$quoteTransfer, 1];
    }

    /**
     * @return array
     */
    protected function getDataWithMultipleShipmentsAnd2ItemsToFranceAnd1ItemToGermany(): array
    {
        $addressBuilder1 = (new AddressBuilder([AddressTransfer::ISO2_CODE => 'FR']));
        $shipmentTransfer1 = (new ShipmentBuilder())
            ->withShippingAddress($addressBuilder1)
            ->withMethod()
            ->build();
        $itemTransfer1 = (new ItemBuilder())->build();
        $itemTransfer1->setShipment($shipmentTransfer1);

        $itemTransfer2 = (new ItemBuilder())->build();
        $itemTransfer2->setShipment($shipmentTransfer1);

        $addressBuilder2 = (new AddressBuilder([AddressTransfer::ISO2_CODE => 'DE']));
        $shipmentTransfer2 = (new ShipmentBuilder())
            ->withShippingAddress($addressBuilder2)
            ->withMethod()
            ->build();
        $itemTransfer3 = (new ItemBuilder())->build();
        $itemTransfer3->setShipment($shipmentTransfer2);

        $quoteTransfer = (new QuoteBuilder())
            ->withBillingAddress()
            ->withTotals()
            ->withCustomer()
            ->withCurrency()
            ->build();

        $quoteTransfer->addItem($itemTransfer1);
        $quoteTransfer->addItem($itemTransfer2);
        $quoteTransfer->addItem($itemTransfer3);

        return [$quoteTransfer, 2];
    }

    /**
     * @return array
     */
    protected function getDataWithMultipleShipmentsAnd3ItemsToFrance(): array
    {
        $addressBuilder1 = (new AddressBuilder([AddressTransfer::ISO2_CODE => 'FR']));
        $shipmentTransfer1 = (new ShipmentBuilder())
            ->withShippingAddress($addressBuilder1)
            ->withMethod()
            ->build();

        $itemTransfer1 = (new ItemBuilder())->build();
        $itemTransfer1->setShipment($shipmentTransfer1);

        $itemTransfer2 = (new ItemBuilder())->build();
        $itemTransfer2->setShipment($shipmentTransfer1);

        $itemTransfer3 = (new ItemBuilder())->build();
        $itemTransfer3->setShipment($shipmentTransfer1);

        $quoteTransfer = (new QuoteBuilder())
            ->withBillingAddress()
            ->withTotals()
            ->withCustomer()
            ->withCurrency()
            ->build();

        $quoteTransfer->addItem($itemTransfer1);
        $quoteTransfer->addItem($itemTransfer2);
        $quoteTransfer->addItem($itemTransfer3);

        return [$quoteTransfer, 1];
    }
}
