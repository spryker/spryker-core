<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Sales\Business\Facade;

use Codeception\TestCase\Test;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\SequenceNumberSettingsBuilder;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Generated\Shared\Transfer\SequenceNumberSettingsTransfer;
use Generated\Shared\Transfer\StockProductTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddressQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Shared\Sales\SalesConstants;
use Spryker\Zed\Sales\Business\SalesBusinessFactory;
use Spryker\Zed\Sales\Business\SalesFacadeInterface;
use Spryker\Zed\Sales\SalesConfig;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Sales
 * @group Business
 * @group Facade
 * @group ShippingAddressSaveTest
 * Add your own group annotations below this line
 */
class ShippingAddressSaveTest extends Test
{
    /**
     * @var \SprykerTest\Zed\Sales\SalesBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $productTransfer = $this->tester->haveProduct();
        $this->tester->haveProductInStock([StockProductTransfer::SKU => $productTransfer->getSku()]);
    }

    /**
     * @dataProvider saveOrderAddressShouldPersistAddressEntityDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function testSaveOrderAddressShouldPersistAddressEntity(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer): void
    {
        // Arrange
        $salesOrderQuery = SpySalesOrderQuery::create()->orderByIdSalesOrder(Criteria::DESC);
        $salesOrderAddressQuery = SpySalesOrderAddressQuery::create();

        // Act
        $this->getSalesFacadeWithMockedConfig()->saveSalesOrder($quoteTransfer, $saveOrderTransfer);

        // Assert
        $this->assertEquals(2, $salesOrderAddressQuery->count(), 'Shipping address and billing address should have been saved');
        $this->assertNotNull($salesOrderQuery->findOne()->getShippingAddress(), 'Shipping address should have been assigned on sales order level.');
    }

    /**
     * @dataProvider saveOrderAddressShouldNotPersistAddressEntityDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function testSaveOrderAddressShouldNotPersistAddressEntity(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer): void
    {
        // Arrange
        $salesOrderQuery = SpySalesOrderQuery::create()->orderByIdSalesOrder(Criteria::DESC);
        $salesOrderAddressQuery = SpySalesOrderAddressQuery::create();

        // Act
        $this->getSalesFacadeWithMockedConfig()->saveSalesOrder($quoteTransfer, $saveOrderTransfer);

        // Assert
        $this->assertEquals(1, $salesOrderAddressQuery->count(), 'Only billing address should have been saved.');
        $this->assertNull($salesOrderQuery->findOne()->getShippingAddress(), 'Shipping address should not have been assigned on sales order level.');
    }

    /**
     * @return array
     */
    public function saveOrderAddressShouldPersistAddressEntityDataProvider(): array
    {
        return [
            'with quote level shipping address' => $this->getDataWithQuoteLevelShippingAddress(),
        ];
    }

    /**
     * @return array
     */
    public function saveOrderAddressShouldNotPersistAddressEntityDataProvider(): array
    {
        return [
            'without quote level shipping address' => $this->getDataWithoutQuoteLevelShippingAddress(),
        ];
    }

    /**
     * @return array
     */
    protected function getDataWithQuoteLevelShippingAddress(): array
    {
        $quoteTransfer = (new QuoteBuilder())
            ->withShippingAddress()
            ->withAnotherBillingAddress()
            ->withItem()
            ->withTotals()
            ->withCustomer()
            ->withCurrency()
            ->build();

        return [$quoteTransfer, new SaveOrderTransfer()];
    }

    /**
     * @return array
     */
    protected function getDataWithoutQuoteLevelShippingAddress(): array
    {
        $quoteTransfer = (new QuoteBuilder())
            ->withBillingAddress()
            ->withItem()
            ->withTotals()
            ->withCustomer()
            ->withCurrency()
            ->build();

        return [$quoteTransfer, new SaveOrderTransfer()];
    }

    /**
     * @return \Spryker\Zed\Sales\Business\SalesFacadeInterface
     */
    protected function getSalesFacadeWithMockedConfig(): SalesFacadeInterface
    {
        $salesFacade = $this->tester->getFacade();
        $salesBusinessFactory = new SalesBusinessFactory();

        $mockedSalesConfig = $this->getMockBuilder(SalesConfig::class)->disableOriginalConstructor()->getMock();
        $mockedSalesConfig->method('determineProcessForOrderItem')->willReturn('DummyPayment01');
        $mockedSalesConfig->method('getOrderReferenceDefaults')->willReturn(
            $this->createSequenceNumberSettingsTransfer()
        );

        $salesBusinessFactory->setConfig($mockedSalesConfig);
        $salesFacade->setFactory($salesBusinessFactory);

        return $salesFacade;
    }

    /**
     * Defines the prefix for the sequence number which is the public id of an order.
     *
     * @return \Generated\Shared\Transfer\SequenceNumberSettingsTransfer
     */
    protected function createSequenceNumberSettingsTransfer(): SequenceNumberSettingsTransfer
    {
        return (new SequenceNumberSettingsBuilder([
            'name' => SalesConstants::NAME_ORDER_REFERENCE,
            'prefix' => 'DE--',
        ]))->build();
    }
}
