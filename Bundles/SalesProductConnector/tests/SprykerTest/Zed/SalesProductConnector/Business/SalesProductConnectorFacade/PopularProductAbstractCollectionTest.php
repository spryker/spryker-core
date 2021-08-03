<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesProductConnector\Business\SalesProductConnectorFacade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\ItemTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use SprykerTest\Zed\Sales\Helper\BusinessHelper;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesProductConnector
 * @group Business
 * @group SalesProductConnectorFacade
 * @group PopularProductAbstractCollectionTest
 * Add your own group annotations below this line
 */
class PopularProductAbstractCollectionTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\SalesProductConnector\SalesProductConnectorBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->tester->ensureSalesOrderDatabaseTableIsEmpty(SpySalesOrderQuery::create());

        $this->tester->configureTestStateMachine([BusinessHelper::DEFAULT_OMS_PROCESS_NAME]);
    }

    /**
     * @return void
     */
    public function testPopularProductAbstractCollectionEmptyTest(): void
    {
        // Act
        $productPageLoadTransfer = $this->tester->getFacade()->getProductPageLoadTransferForRefresh();

        // Assert
        $this->assertEmpty($productPageLoadTransfer->getProductAbstractIds());
    }

    /**
     * @return void
     */
    public function testGetProductPageLoadTransferForOnceOrderTest(): void
    {
        // Arrange
        $productTransfer = $this->tester->haveProduct();

        $quoteTransfer = (new QuoteBuilder())
            ->withItem([
                ItemTransfer::SKU => $productTransfer->getSku(),
                ItemTransfer::UNIT_PRICE => 1,
                ItemTransfer::QUANTITY => 10,
            ])
            ->withCustomer()
            ->withTotals()
            ->withShippingAddress()
            ->withBillingAddress()
            ->withCurrency()
            ->build();

        $this->tester->haveOrderFromQuote($quoteTransfer, BusinessHelper::DEFAULT_OMS_PROCESS_NAME);

        // Act
        $productPageLoadTransfer = $this->tester->getFacade()->getProductPageLoadTransferForRefresh();

        // Assert
        $this->assertCount(1, $productPageLoadTransfer->getProductAbstractIds());
    }

    /**
     * @return void
     */
    public function testGetProductPageLoadTransferForMultipleOrdersTest(): void
    {
        // Arrange
        $productTransfer1 = $this->tester->haveProduct();
        $productTransfer2 = $this->tester->haveProduct();

        $quoteTransfer1 = (new QuoteBuilder())
            ->withItem([
                ItemTransfer::SKU => $productTransfer1->getSku(),
                ItemTransfer::UNIT_PRICE => 1,
                ItemTransfer::QUANTITY => 10,
            ])
            ->withCustomer()
            ->withTotals()
            ->withShippingAddress()
            ->withBillingAddress()
            ->withCurrency()
            ->build();

        $quoteTransfer2 = (new QuoteBuilder())
            ->withItem([
                ItemTransfer::SKU => $productTransfer2->getSku(),
                ItemTransfer::UNIT_PRICE => 1,
                ItemTransfer::QUANTITY => 10,
            ])
            ->withCustomer()
            ->withTotals()
            ->withShippingAddress()
            ->withBillingAddress()
            ->withCurrency()
            ->build();

        $this->tester->haveOrderFromQuote($quoteTransfer1, BusinessHelper::DEFAULT_OMS_PROCESS_NAME);
        $this->tester->haveOrderFromQuote($quoteTransfer2, BusinessHelper::DEFAULT_OMS_PROCESS_NAME);

        // Act
        $productPageLoadTransfer = $this->tester->getFacade()->getProductPageLoadTransferForRefresh();

        // Assert
        $this->assertCount(2, $productPageLoadTransfer->getProductAbstractIds());
    }
}
