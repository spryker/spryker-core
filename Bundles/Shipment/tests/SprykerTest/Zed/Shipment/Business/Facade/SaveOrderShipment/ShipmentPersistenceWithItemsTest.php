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
use Orm\Zed\Sales\Persistence\Map\SpySalesShipmentTableMap;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Orm\Zed\Sales\Persistence\SpySalesShipmentQuery;
use Propel\Runtime\Formatter\SimpleArrayFormatter;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Shipment
 * @group Business
 * @group Facade
 * @group SaveOrderShipment
 * @group ShipmentPersistenceWithItemsTest
 * Add your own group annotations below this line
 */
class ShipmentPersistenceWithItemsTest extends Test
{
    /**
     * @var \SprykerTest\Zed\Shipment\ShipmentBusinessTester
     */
    protected $tester;

    /**
     * @dataProvider shipmentOrderSaverShouldUseQuoteLevelShipmentAndItemsDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function testShipmentOrderSaverShouldUseQuoteLevelShipmentAndItems(
        QuoteTransfer $quoteTransfer
    ): void {
        // Arrange
        $saveOrderTransfer = $this->tester->createOrderWithoutShipment($quoteTransfer);

        $salesOrderItemsQuery = SpySalesOrderItemQuery::create()->filterByFkSalesOrder($saveOrderTransfer->getIdSalesOrder());
        $salesShipmentQuery = SpySalesShipmentQuery::create()->filterByFkSalesOrder($saveOrderTransfer->getIdSalesOrder());

        // Act
        $this->tester->getFacade()->saveOrderShipment($quoteTransfer, $saveOrderTransfer);

        // Assert
        $salesShipmentEntity = $salesShipmentQuery->findOne();
        $salesOrderItemsEntities = $salesOrderItemsQuery->find();

        $this->assertCount($quoteTransfer->getItems()->count(), $salesOrderItemsEntities, 'Order shipment has no any related addresses been saved.');
        foreach ($salesOrderItemsEntities as $i => $salesOrderItemEntity) {
            $this->assertEquals($salesOrderItemEntity->getFkSalesShipment(), $salesShipmentEntity->getIdSalesShipment(), sprintf('Order shipment is not related with order item (iteration #%d).', $i));
        }
    }

    /**
     * @dataProvider shipmentOrderSaverShouldUseMultipleShipmentsWithItemsDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int $countOfNewShipments
     *
     * @return void
     */
    public function testShipmentOrderSaverShouldUseMultipleShipmentsWithItems(
        QuoteTransfer $quoteTransfer,
        int $countOfNewShipments
    ): void {
        // Arrange
        $savedOrderTransfer = $this->tester->createOrderWithoutShipment($quoteTransfer);

        $salesOrderItemQuery = SpySalesOrderItemQuery::create()->filterByFkSalesOrder($savedOrderTransfer->getIdSalesOrder());

        $idSalesShipmentQuery = SpySalesShipmentQuery::create()
            ->filterByFkSalesOrder($savedOrderTransfer->getIdSalesOrder())
            ->select(SpySalesShipmentTableMap::COL_ID_SALES_SHIPMENT)
            ->setFormatter(SimpleArrayFormatter::class);

        // Act
        $this->tester->getFacade()->saveOrderShipment($quoteTransfer, $savedOrderTransfer);

        // Assert
        $salesOrderItemEntities = $salesOrderItemQuery->find();
        $idSalesShipmentEntities = $idSalesShipmentQuery->find()->getData();

        $this->assertCount($countOfNewShipments, $idSalesShipmentEntities, 'Saved order shipments count mismatch!');
        foreach ($salesOrderItemEntities as $i => $salesOrderItemEntity) {
            $this->assertContains($salesOrderItemEntity->getFkSalesShipment(), $idSalesShipmentEntities, sprintf('Order item is not related with order shipment (iteration #%d).', $i));
        }
    }

    /**
     * @return array
     */
    public function shipmentOrderSaverShouldUseQuoteLevelShipmentAndItemsDataProvider(): array
    {
        return [
            'any data, 2 items; expected: shipment connected to order items in DB' => $this->getDataWithQuoteLevelShipmentAnd2ItemsToFrance(),
        ];
    }

    /**
     * @return array
     */
    public function shipmentOrderSaverShouldUseMultipleShipmentsWithItemsDataProvider(): array
    {
        return [
            'France 1 item; expected: 1 order shipment connected to order item in DB' => $this->getDataWithMultipleShipmentsAnd1ItemToFrance(),
            'France 2 items, Germany 1 item; expected: 2 order shipments connected to order items in DB' => $this->getDataWithMultipleShipmentsAnd2ItemsToFranceAnd1ItemToGermany(),
        ];
    }

    /**
     * @return array
     */
    protected function getDataWithQuoteLevelShipmentAnd2ItemsToFrance(): array
    {
        $addressBuilder = (new AddressBuilder([AddressTransfer::ISO2_CODE => 'FR']));

        $shipmentBuilder = (new ShipmentBuilder())
            ->withShippingAddress($addressBuilder)
            ->withMethod();

        $quoteTransfer = (new QuoteBuilder())
            ->withShipment($shipmentBuilder)
            ->withItem()
            ->withAnotherItem()
            ->withShippingAddress($addressBuilder)
            ->withAnotherBillingAddress()
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
}
