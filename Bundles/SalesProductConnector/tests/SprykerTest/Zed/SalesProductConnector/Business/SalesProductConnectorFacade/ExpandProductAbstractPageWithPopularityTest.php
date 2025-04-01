<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesProductConnector\Business\SalesProductConnectorFacade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ProductPayloadBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductPageLoadTransfer;
use Generated\Shared\Transfer\ProductPayloadTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesProductConnector
 * @group Business
 * @group SalesProductConnectorFacade
 * @group ExpandProductAbstractPageWithPopularityTest
 * Add your own group annotations below this line
 */
class ExpandProductAbstractPageWithPopularityTest extends Unit
{
    /**
     * @var int
     */
    protected const FAKE_ID_PRODUCT_ABSTRACT = 6666;

    /**
     * @var string
     */
    protected const DEFAULT_OMS_PROCESS_NAME = 'DummyPayment01';

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

        $this->tester->configureTestStateMachine([static::DEFAULT_OMS_PROCESS_NAME]);
    }

    /**
     * @return void
     */
    public function testExpandProductAbstractPageWithEmptyPopularityTest(): void
    {
        // Arrange
        $productPageLoadTransfer = (new ProductPageLoadTransfer())
            ->setProductAbstractIds([
                static::FAKE_ID_PRODUCT_ABSTRACT,
            ])
            ->setPayloadTransfers([
                $this->getProductPayloadTransfer([
                    ProductPayloadTransfer::ID_PRODUCT_ABSTRACT => static::FAKE_ID_PRODUCT_ABSTRACT,
                ]),
            ]);

        // Act
        $expandedProductPageLoadTransfer = $this->tester->getFacade()
            ->expandProductAbstractPageWithPopularity($productPageLoadTransfer);

        // Assert
        /** @var \Generated\Shared\Transfer\ProductPayloadTransfer $payloadTransfer */
        foreach ($expandedProductPageLoadTransfer->getPayloadTransfers() as $payloadTransfer) {
            $this->assertSame(0, $payloadTransfer->getPopularity());
        }
    }

    /**
     * @return void
     */
    public function testExpandProductAbstractPageWithNotEmptyPopularityTest(): void
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
            ->withStore()
            ->build();

        $this->tester->haveOrderFromQuote($quoteTransfer, static::DEFAULT_OMS_PROCESS_NAME);

        $productPageLoadTransfer = (new ProductPageLoadTransfer())
            ->setProductAbstractIds([
                $productTransfer->getFkProductAbstract(),
            ])
            ->setPayloadTransfers([
                $this->getProductPayloadTransfer([
                    ProductPayloadTransfer::ID_PRODUCT_ABSTRACT => $productTransfer->getFkProductAbstract(),
                ]),
            ]);

        // Act
        $expandedProductPageLoadTransfer = $this->tester->getFacade()
            ->expandProductAbstractPageWithPopularity($productPageLoadTransfer);

        // Assert
        /** @var \Generated\Shared\Transfer\ProductPayloadTransfer $payloadTransfer */
        foreach ($expandedProductPageLoadTransfer->getPayloadTransfers() as $payloadTransfer) {
            $this->assertSame(10, $payloadTransfer->getPopularity());
        }
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\ProductPayloadTransfer
     */
    protected function getProductPayloadTransfer(array $seedData): ProductPayloadTransfer
    {
        return (new ProductPayloadBuilder())->seed($seedData)->build();
    }
}
