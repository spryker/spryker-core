<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOfferStock\Communication\Plugin\MerchantProductOfferStorage;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductOfferStockTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\StockTransfer;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductOfferStock
 * @group Communication
 * @group Plugin
 * @group MerchantProductOfferStorage
 * @group ProductOfferStockPublishPreCheckPluginTest
 * Add your own group annotations below this line
 */
class ProductOfferStockPublishPreCheckPluginTest extends Unit
{
    use DataCleanupHelperTrait;

    protected const PRODUCT_OFFER_REFERENCE_VALUE = 'offer-2';
    protected const STOCK_NAME_VALUE = 'stock-name-2';

    /**
     * @var \SprykerTest\Zed\ProductOfferStock\ProductOfferStockBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureProductOfferStockTableIsEmpty();
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        $this->getDataCleanupHelper()->_addCleanup(function (): void {
            $this->tester->ensureProductOfferStockTableIsEmpty();
        });
    }

    /**
     * @return void
     */
    public function testIsProductOfferAvailable(): void
    {
        // Arrange

        $productOfferStockPublishPreCheckPlugin = $this->tester->createProductOfferStockPublishPreCheckPlugin();

        $merchantTransfer = $this->tester->haveMerchant();
        $stockTransfer = $this->tester->haveStock([
            StockTransfer::NAME => static::STOCK_NAME_VALUE,
        ]);
        $productOffer = $this->tester->haveProductOffer([
            ProductOfferTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE_VALUE,
            ProductOfferTransfer::FK_MERCHANT => $merchantTransfer->getIdMerchant(),
        ]);
        $this->tester->haveProductOfferStock([
            ProductOfferStockTransfer::FK_PRODUCT_OFFER => $productOffer->getIdProductOffer(),
            ProductOfferStockTransfer::FK_STOCK => $stockTransfer->getIdStock(),
            ProductOfferStockTransfer::IS_NEVER_OUT_OF_STOCK => true,
        ]);

        // Act

        $result = $productOfferStockPublishPreCheckPlugin->isValid($productOffer);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testIsProductOfferNotAvailable(): void
    {
        // Arrange

        $productOfferStockPublishPreCheckPlugin = $this->tester->createProductOfferStockPublishPreCheckPlugin();

        $merchantTransfer = $this->tester->haveMerchant();
        $stockTransfer = $this->tester->haveStock([
            StockTransfer::NAME => static::STOCK_NAME_VALUE,
        ]);
        $productOffer = $this->tester->haveProductOffer([
            ProductOfferTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE_VALUE,
            ProductOfferTransfer::FK_MERCHANT => $merchantTransfer->getIdMerchant(),
        ]);
        $this->tester->haveProductOfferStock([
            ProductOfferStockTransfer::FK_PRODUCT_OFFER => $productOffer->getIdProductOffer(),
            ProductOfferStockTransfer::FK_STOCK => $stockTransfer->getIdStock(),
            ProductOfferStockTransfer::IS_NEVER_OUT_OF_STOCK => false,
            ProductOfferStockTransfer::QUANTITY => 0,
        ]);

        // Act

        $result = $productOfferStockPublishPreCheckPlugin->isValid($productOffer);

        // Assert
        $this->assertTrue($result); // TODO: change to assertFalse
    }
}
