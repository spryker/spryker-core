<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Shipment\Business\Facade;

use Exception;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use BadMethodCallException;
use Codeception\TestCase\Test;
use Generated\Shared\DataBuilder\AddressBuilder;
use Generated\Shared\DataBuilder\ExpenseBuilder;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\SaveOrderBuilder;
use Generated\Shared\DataBuilder\SequenceNumberSettingsBuilder;
use Generated\Shared\DataBuilder\ShipmentBuilder;
use Generated\Shared\DataBuilder\ShipmentMethodBuilder;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Generated\Shared\Transfer\SequenceNumberSettingsTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Orm\Zed\Country\Persistence\SpyCountryQuery;
use Orm\Zed\Sales\Persistence\SpySalesExpenseQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddress;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddressQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use Orm\Zed\Sales\Persistence\SpySalesShipmentQuery;
use Orm\Zed\Shipment\Persistence\SpyShipmentCarrier;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethod;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery;
use Orm\Zed\Tax\Persistence\Map\SpyTaxRateTableMap;
use Orm\Zed\Tax\Persistence\Map\SpyTaxSetTableMap;
use Orm\Zed\Tax\Persistence\SpyTaxRate;
use Orm\Zed\Tax\Persistence\SpyTaxRateQuery;
use Orm\Zed\Tax\Persistence\SpyTaxSet;
use Orm\Zed\Tax\Persistence\SpyTaxSetTax;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Shipment\ShipmentConstants;
use Spryker\Shared\Tax\TaxConstants;
use Spryker\Zed\Product\ProductDependencyProvider;
use Spryker\Zed\Shipment\Dependency\ShipmentToTaxBridge;
use Spryker\Zed\Shipment\Dependency\ShipmentToTaxInterface;
use Spryker\Zed\Shipment\Persistence\ShipmentQueryContainer;
use Spryker\Zed\Shipment\ShipmentDependencyProvider;
use Spryker\Zed\Tax\TaxDependencyProvider;
use Spryker\Zed\TaxProductConnector\Business\TaxProductConnectorBusinessFactory;
use Spryker\Zed\TaxProductConnector\Communication\Plugin\TaxSetProductAbstractAfterCreatePlugin;
use Spryker\Zed\TaxProductConnector\Dependency\Facade\TaxProductConnectorToTaxBridge;
use Spryker\Zed\TaxProductConnector\Dependency\Facade\TaxProductConnectorToTaxInterface;
use Spryker\Zed\TaxProductConnector\TaxProductConnectorDependencyProvider;

/**
 * Auto-generated group annotations
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
        $saveOrderTransfer = $this->tester->haveOrderWithoutShipment($quoteTransfer);

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
     * @param int $expectedCountOfShipments
     *
     * @return void
     */
    public function testShipmentOrderSaverShouldUseMultipleShipmentsWithMultipleShipmentsArePersisted(
        QuoteTransfer $quoteTransfer,
        int $countOfNewShipments
    ): void {
        // Arrange
        $saveOrderTransfer = $this->tester->haveOrderWithoutShipment($quoteTransfer);

        $salesShipmentQuery = SpySalesShipmentQuery::create()->filterByFkSalesOrder($saveOrderTransfer->getIdSalesOrder());

        // Act
        $this->tester->getFacade()->saveOrderShipment($quoteTransfer, $saveOrderTransfer);

        // Assert
        $this->assertEquals($countOfNewShipments, $salesShipmentQuery->count(), 'Order shipments count mismatch! There is no shipments have been saved.');
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
        $addressBuilder = (new AddressBuilder(['iso2Code' => 'FR']));

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
        $addressBuilder = (new AddressBuilder(['iso2Code' => 'FR']));

        $shipmentBuilder = (new ShipmentBuilder())
            ->withAnotherShippingAddress($addressBuilder)
            ->withAnotherMethod();

        $itemBuilder = (new ItemBuilder())
            ->withAnotherShipment($shipmentBuilder);

        $quoteTransfer = (new QuoteBuilder())
            ->withAnotherItem($itemBuilder)
            ->withAnotherBillingAddress()
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
        $addressBuilder1 = (new AddressBuilder(['iso2Code' => 'FR']));
        $shipmentTransfer1 = (new ShipmentBuilder())
            ->withAnotherShippingAddress($addressBuilder1)
            ->withAnotherMethod()
            ->build();
        $itemTransfer1 = (new ItemBuilder())->build();
        $itemTransfer1->setShipment($shipmentTransfer1);

        $itemTransfer2 = (new ItemBuilder())->build();
        $itemTransfer2->setShipment($shipmentTransfer1);

        $addressBuilder2 = (new AddressBuilder(['iso2Code' => 'DE']));
        $shipmentTransfer2 = (new ShipmentBuilder())
            ->withAnotherShippingAddress($addressBuilder2)
            ->withAnotherMethod()
            ->build();
        $itemTransfer3 = (new ItemBuilder())->build();
        $itemTransfer3->setShipment($shipmentTransfer2);

        $quoteTransfer = (new QuoteBuilder())
            ->withAnotherBillingAddress()
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
        $addressBuilder1 = (new AddressBuilder(['iso2Code' => 'FR']));
        $shipmentTransfer1 = (new ShipmentBuilder())
            ->withAnotherShippingAddress($addressBuilder1)
            ->withAnotherMethod()
            ->build();

        $itemTransfer1 = (new ItemBuilder())->build();
        $itemTransfer1->setShipment($shipmentTransfer1);

        $itemTransfer2 = (new ItemBuilder())->build();
        $itemTransfer2->setShipment($shipmentTransfer1);

        $itemTransfer3 = (new ItemBuilder())->build();
        $itemTransfer3->setShipment($shipmentTransfer1);

        $quoteTransfer = (new QuoteBuilder())
            ->withAnotherBillingAddress()
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