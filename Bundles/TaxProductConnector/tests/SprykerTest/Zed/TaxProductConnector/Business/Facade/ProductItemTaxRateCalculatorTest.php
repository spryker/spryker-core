<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\TaxProductConnector\Business\Facade;

use BadMethodCallException;
use Codeception\TestCase\Test;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\SequenceNumberSettingsBuilder;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Generated\Shared\Transfer\SequenceNumberSettingsTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddressQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Sales\SalesConstants;
use Spryker\Zed\Sales\Business\SalesBusinessFactory;
use Spryker\Zed\Sales\Business\SalesFacade;
use Spryker\Zed\Sales\Business\SalesFacadeInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group TaxProductConnector
 * @group Business
 * @group Facade
 * @group ProductItemTaxRateCalculatorTest
 * Add your own group annotations below this line
 */
class ProductItemTaxRateCalculatorTest extends Test
{
    /**
     * @var \SprykerTest\Zed\TaxProductConnector\TaxProductConnectorBusinessTester
     */
    protected $tester;

    /**
     * @dataProvider taxRateCalculationShouldUseQuoteShippingAddressDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function testTaxRateCalculationShouldUseQuoteShippingAddress(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer)
    {
//        // Arrange
//        $salesOrderQuery = SpySalesOrderQuery::create()->orderByIdSalesOrder(Criteria::DESC);
//        $shippingAddressTransfer = $quoteTransfer->getShippingAddress();
//        $salesOrderAddressQuery = SpySalesOrderAddressQuery::create()->filterByAddress1($shippingAddressTransfer->getAddress1());
//        $salesFacade = $this->getSalesFacadeWithMockedConfig();
//
//        // Act
//        $salesFacade->saveSalesOrder($quoteTransfer, $saveOrderTransfer);
//
//        // Assert
//        $this->assertTrue($salesOrderAddressQuery->count() === 1, 'Shipping address should have been saved');
//        $this->assertNull($salesOrderQuery->findOne()->getShippingAddress(), 'Shipping address should not have been assigned on sales order level.');
    }

    /**
     * @dataProvider taxRateCalculationShouldUseItemShippingAddressDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function testTaxRateCalculationShouldUseItemShippingAddress(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer)
    {
//        // Arrange
//        $salesOrderQuery = SpySalesOrderQuery::create()->orderByIdSalesOrder(Criteria::DESC);
//        $salesOrderAddressQuery = SpySalesOrderAddressQuery::create();
//        $countBefore = $salesOrderAddressQuery->count();
//        $salesFacade = $this->getSalesFacadeWithMockedConfig();
//
//        // Act
//        $salesFacade->saveSalesOrder($quoteTransfer, $saveOrderTransfer);
//
//        // Assert
//        $expectedOrderAddressCount = $countBefore + 1;
//        $this->assertEquals($expectedOrderAddressCount, $salesOrderAddressQuery->count(), 'Address count mismatch! Only billing address should have been saved.');
//        $this->assertNull($salesOrderQuery->findOne()->getShippingAddress(), 'Shipping address should not have been assigned on sales order level.');
    }

    /**
     * @dataProvider taxRateCalculationShouldBeDefaultDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function testTaxRateCalculationShouldBeDefault(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer)
    {
//        // Arrange
//        $salesOrderQuery = SpySalesOrderQuery::create()->orderByIdSalesOrder(Criteria::DESC);
//        $salesOrderAddressQuery = SpySalesOrderAddressQuery::create();
//        $countBefore = $salesOrderAddressQuery->count();
//        $salesFacade = $this->getSalesFacadeWithMockedConfig();
//
//        // Act
//        $salesFacade->saveSalesOrder($quoteTransfer, $saveOrderTransfer);
//
//        // Assert
//        $expectedOrderAddressCount = $countBefore + 1;
//        $this->assertEquals($expectedOrderAddressCount, $salesOrderAddressQuery->count(), 'Address count mismatch! Only billing address should have been saved.');
//        $this->assertNull($salesOrderQuery->findOne()->getShippingAddress(), 'Shipping address should not have been assigned on sales order level.');
    }

    /**
     * @return array
     */
    public function taxRateCalculationShouldUseQuoteShippingAddressDataProvider()
    {
        return [
            'with quote level shipping address' => $this->getDataWithQuoteLevelShippingAddress(),
        ];
    }

    /**
     * @return array
     */
    public function taxRateCalculationShouldUseItemShippingAddressDataProvider()
    {
        return [
            'without quote level shipping address, with item level shipping addresses' => $this->getDataWithoutQuoteLevelShippingAddress(),
        ];
    }

    /**
     * @return array
     */
    public function taxRateCalculationShouldBeDefaultDataProvider()
    {
        return [
            'without quote and item level shipping addresses' => $this->getDataWithoutQuoteLevelShippingAddress(),
        ];
    }

    /**
     * @return array
     */
    protected function getDataWithQuoteLevelShippingAddress()
    {
        $itemTransferBuilder1 = $this->createItemTransferBuilder(1001);
        $itemTransferBuilder2 = $this->createItemTransferBuilder(2002);

        $quoteTransfer = (new QuoteBuilder())
            ->withShippingAddress()
            ->withAnotherBillingAddress()
            ->withAnotherItem($itemTransferBuilder1)
            ->withAnotherItem($itemTransferBuilder2)
            ->withTotals()
            ->withCustomer()
            ->withCurrency()
            ->build();

        return [$quoteTransfer, new SaveOrderTransfer()];
    }

    /**
     * @return array
     */
    protected function getDataWithoutQuoteLevelShippingAddress()
    {
        $itemTransferBuilder1 = $this->createItemTransferBuilder(1001);
        $itemTransferBuilder2 = $this->createItemTransferBuilder(2002);

        $quoteTransfer = (new QuoteBuilder())
            ->withAnotherBillingAddress()
            ->withAnotherItem($itemTransferBuilder1)
            ->withAnotherItem($itemTransferBuilder2)
            ->withTotals()
            ->withCustomer()
            ->withCurrency()
            ->build();

        return [$quoteTransfer, new SaveOrderTransfer()];
    }

    /**
     * @return \Spryker\Zed\Sales\Business\SalesFacadeInterface
     */
    protected function getSalesFacadeWithMockedConfig(): SalesFacadeInterface {
        $salesFacade = $this->createSalesFacade();
        $salesBusinessFactory = $this->createBusinessFactory();

        $salesConfigMock = $this->createSalesConfigMock();
        $salesConfigMock->method('determineProcessForOrderItem')->willReturn('DummyPayment01');
        $salesConfigMock->method('getOrderReferenceDefaults')->willReturn(
            $this->createSequenceNumberSettingsTransfer()
        );

        $salesBusinessFactory->setConfig($salesConfigMock);
        $salesFacade->setFactory($salesBusinessFactory);

        return $salesFacade;
    }

    /**
     * @param int|null $unitPrice
     *
     * @return \Generated\Shared\DataBuilder\ItemBuilder
     */
    protected function createItemTransferBuilder($unitPrice = null): ItemBuilder
    {
        return (new ItemBuilder([
            'unitPrice' => $unitPrice,
        ]));
    }

    /**
     * @return \Spryker\Zed\Sales\Business\SalesFacadeInterface
     */
    protected function createSalesFacade(): SalesFacadeInterface
    {
        return new SalesFacade();
    }

    /**
     * @return \Spryker\Zed\Sales\Business\SalesBusinessFactory
     */
    protected function createBusinessFactory(): SalesBusinessFactory
    {
        return new SalesBusinessFactory();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Sales\SalesConfig
     */
    protected function createSalesConfigMock(): SalesConfig
    {
        return $this->getMockBuilder(SalesConfig::class)->getMock();
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