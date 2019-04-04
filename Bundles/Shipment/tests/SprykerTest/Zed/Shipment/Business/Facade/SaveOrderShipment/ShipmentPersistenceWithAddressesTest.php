<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Shipment\Business\Facade\SaveOrderShipment;

use Exception;
use Orm\Zed\Sales\Persistence\Map\SpySalesExpenseTableMap;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderAddressTableMap;
use Orm\Zed\Sales\Persistence\Map\SpySalesShipmentTableMap;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Propel\Runtime\Formatter\SimpleArrayFormatter;
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
     * @dataProvider shipmentOrderSaverShouldUseQuoteLevelShipmentAndShippingAddressDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function testShipmentOrderSaverShouldUseQuoteLevelShipmentAndShippingAddress(
        QuoteTransfer $quoteTransfer
    ): void {
        // Arrange
        $saveOrderTransfer = $this->tester->haveOrderWithoutShipment($quoteTransfer);
        // @todo Create shipping address.

        $salesAddressQuery = SpySalesOrderAddressQuery::create();
        $salesShipmentQuery = SpySalesShipmentQuery::create()->filterByFkSalesOrder($saveOrderTransfer->getIdSalesOrder());
        $salesOrderQuery = SpySalesOrderQuery::create()->filterByIdSalesOrder($saveOrderTransfer->getIdSalesOrder());

        // Act
        $this->tester->getFacade()->saveOrderShipment($quoteTransfer, $saveOrderTransfer);

        // Assert
        $salesShipmentEntity = $salesShipmentQuery->findOne();
        $salesOrderEntity = $salesOrderQuery->findOne();

        $this->assertNull($salesOrderEntity->getFkSalesOrderAddressShipping(), 'Order level shipping address should be null');
        $this->assertNotNull($salesShipmentEntity->getFkSalesOrderAddress(), 'Order address could not be found! There is no any order address has been saved.');
    }

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
        $savedOrderTransfer = $this->tester->haveOrderWithoutShipment($quoteTransfer);

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
        $salesShipmentEntityList = $salesShipmentQuery->find();
        $idSalesAddressList = $idSalesAddressQuery->find()->getData();

        $this->assertEquals($countOfNewShippingAddresses, count($idSalesAddressList), 'Order shipping addresses count mismatch! There is no order shipping addresses have been saved.');
        foreach ($salesShipmentEntityList as $salesShipmentEntity) {
            $this->assertContains($salesShipmentEntity->getFkSalesOrderAddress(), $idSalesAddressList, 'Order shipment address is not related with order shipment.');
        }
    }

    /**
     * @return array
     */
    public function shipmentOrderSaverShouldUseQuoteLevelShipmentAndShippingAddressDataProvider(): array
    {
        return [
            'any data, 1 address; expected: shipment with shipping address in DB' => $this->getDataWithQuoteLevelShipmentAndShippingAddressToFrance(),
        ];
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
    protected function getDataWithQuoteLevelShipmentAndShippingAddressToFrance(): array
    {
        $addressBuilder = (new AddressBuilder(['iso2Code' => 'FR']));

        $shipmentBuilder = (new ShipmentBuilder())
            ->withAnotherShippingAddress($addressBuilder)
            ->withAnotherMethod();

        $quoteTransfer = (new QuoteBuilder())
            ->withAnotherShipment($shipmentBuilder)
            ->withAnotherItem()
            ->withAnotherShippingAddress($addressBuilder)
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
    protected function getDataWithMultipleShipmentsAndShippingAddressesAnd1ItemToFrance(): array
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
    protected function getDataWithMultipleShipmentsAndShippingAddressesAnd2ItemsToFranceAnd1ItemToGermany(): array
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
}