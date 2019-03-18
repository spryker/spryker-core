<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Sales\Business\Facade;

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
        $shippingAddressTransfer = $quoteTransfer->getShippingAddress();
        $salesOrderAddressQuery = SpySalesOrderAddressQuery::create()->filterByAddress1($shippingAddressTransfer->getAddress1());
        $salesFacade = $this->getSalesFacadeWithMockedConfig();

        // Act
        $salesFacade->saveSalesOrder($quoteTransfer, $saveOrderTransfer);

        // Assert
        $this->assertTrue($salesOrderAddressQuery->count() === 1, 'Shipping address should have been saved');
        $this->assertNull($salesOrderQuery->findOne()->getShippingAddress(), 'Shipping address should not have been assigned on sales order level.');
    }

    /**
     * @dataProvider saveOrderAddressShouldntPersistAddressEntityDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function testSaveOrderAddressShouldntPersistAddressEntity(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer): void
    {
        // Arrange
        $salesOrderQuery = SpySalesOrderQuery::create()->orderByIdSalesOrder(Criteria::DESC);
        $salesOrderAddressQuery = SpySalesOrderAddressQuery::create();
        $countBefore = $salesOrderAddressQuery->count();
        $salesFacade = $this->getSalesFacadeWithMockedConfig();

        // Act
        $salesFacade->saveSalesOrder($quoteTransfer, $saveOrderTransfer);

        // Assert
        $expectedOrderAddressCount = $countBefore + 1;
        $this->assertEquals($expectedOrderAddressCount, $salesOrderAddressQuery->count(), 'Address count mismatch! Only billing address should have been saved.');
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
    public function saveOrderAddressShouldntPersistAddressEntityDataProvider(): array
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
        $itemBuilder1 = $this->createItemTransferBuilder(['unitPrice' => 1001]);
        $itemBuilder2 = $this->createItemTransferBuilder(['unitPrice' => 2002]);

        $quoteTransfer = (new QuoteBuilder())
            ->withShippingAddress()
            ->withAnotherBillingAddress()
            ->withAnotherItem($itemBuilder1)
            ->withAnotherItem($itemBuilder2)
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
        $itemBuilder1 = $this->createItemTransferBuilder(['unitPrice' => 1001]);
        $itemBuilder2 = $this->createItemTransferBuilder(['unitPrice' => 2002]);

        $quoteTransfer = (new QuoteBuilder())
            ->withAnotherBillingAddress()
            ->withAnotherItem($itemBuilder1)
            ->withAnotherItem($itemBuilder2)
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
     * @param array $seed
     *
     * @return \Generated\Shared\DataBuilder\ItemBuilder
     */
    protected function createItemTransferBuilder(array $seed = []): ItemBuilder
    {
        return new ItemBuilder($seed);
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
