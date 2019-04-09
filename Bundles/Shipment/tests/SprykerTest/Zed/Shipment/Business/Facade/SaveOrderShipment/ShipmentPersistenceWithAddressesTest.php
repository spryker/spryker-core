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
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderAddressTableMap;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddressQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use Orm\Zed\Sales\Persistence\SpySalesShipmentQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Formatter\SimpleArrayFormatter;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Shipment
 * @group Business
 * @group Facade
 * @group SaveOrderShipment
 * @group ShipmentPersistenceWithAddressesTest
 * Add your own group annotations below this line
 */
class ShipmentPersistenceWithAddressesTest extends Test
{
    /**
     * @var \SprykerTest\Zed\Shipment\ShipmentBusinessTester
     */
    protected $tester;

    /**
     * @dataProvider shipmentOrderSaverShouldUseMultipleShipmentsWithMultipleShipmentAddressesDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int $countOfNewShippingAddresses
     *
     * @return void
     */
    public function testShipmentOrderSaverShouldUseMultipleShipmentsWithMultipleShipmentAddresses(
        QuoteTransfer $quoteTransfer,
        int $countOfNewShippingAddresses
    ): void {
        // Arrange
        $savedOrderTransfer = $this->tester->createOrderWithoutShipment($quoteTransfer);

        $salesShipmentQuery = SpySalesShipmentQuery::create()->filterByFkSalesOrder($savedOrderTransfer->getIdSalesOrder());

        $salesOrderEntity = SpySalesOrderQuery::create()
            ->filterByIdSalesOrder($savedOrderTransfer->getIdSalesOrder())
            ->findOne();
        $idSalesAddressQuery = SpySalesOrderAddressQuery::create()
            ->filterByIdSalesOrderAddress($salesOrderEntity->getFkSalesOrderAddressBilling(), Criteria::NOT_EQUAL)
            ->useSpySalesShipmentQuery()
                ->filterByFkSalesOrder($savedOrderTransfer->getIdSalesOrder())
            ->endUse()
            ->select(SpySalesOrderAddressTableMap::COL_ID_SALES_ORDER_ADDRESS)
            ->setFormatter(SimpleArrayFormatter::class);

        // Act
        $this->tester->getFacade()->saveOrderShipment($quoteTransfer, $savedOrderTransfer);

        // Assert
        $salesShipmentEntities = $salesShipmentQuery->find();
        $idSalesAddressList = $idSalesAddressQuery->find()->getData();

        $this->assertCount($countOfNewShippingAddresses, $idSalesAddressList, 'Saved order shipping addresses count mismatch!');
        foreach ($salesShipmentEntities as $i => $salesShipmentEntity) {
            $this->assertContains($salesShipmentEntity->getFkSalesOrderAddress(), $idSalesAddressList, sprintf('Order shipment address is not related with order shipment (iteration #%d).', $i));
        }
    }

    /**
     * @return array
     */
    public function shipmentOrderSaverShouldUseMultipleShipmentsWithMultipleShipmentAddressesDataProvider(): array
    {
        return [
            'France 1 item, 1 address; expected: 1 order shipment with shipping address in DB' => $this->getDataWithMultipleShipmentsAndShippingAddressesAnd1ItemToFrance(),
            'France 2 items, Germany 1 item, 2 addresses; expected: 2 order shipments with shipping addresses in DB' => $this->getDataWithMultipleShipmentsAndShippingAddressesAnd2ItemsToFranceAnd1ItemToGermany(),
        ];
    }

    /**
     * @return array
     */
    protected function getDataWithMultipleShipmentsAndShippingAddressesAnd1ItemToFrance(): array
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
    protected function getDataWithMultipleShipmentsAndShippingAddressesAnd2ItemsToFranceAnd1ItemToGermany(): array
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
