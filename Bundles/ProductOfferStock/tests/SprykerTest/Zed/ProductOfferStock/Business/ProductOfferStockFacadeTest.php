<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOfferStock\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductOfferStockCriteriaFilterTransfer;
use Generated\Shared\Transfer\ProductOfferStockRequestTransfer;
use Generated\Shared\Transfer\ProductOfferStockTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductOfferStock
 * @group Business
 * @group Facade
 * @group ProductOfferStockFacadeTest
 * Add your own group annotations below this line
 */
class ProductOfferStockFacadeTest extends Unit
{
    use DataCleanupHelperTrait;

    protected const PRODUCT_OFFER_REFERENCE_VALUE = 'offer-1';
    protected const STOCK_NAME_VALUE = 'stock-name-1';

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
    public function testIsProductOfferNeverOutOfStock(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->haveMerchant();
        $storeTransfer = $this->tester->haveStore([
            StoreTransfer::NAME => 'DE',
        ]);
        $stockTransfer = $this->tester->haveStock([
            StockTransfer::NAME => static::STOCK_NAME_VALUE,
            'storeRelation' => ['idStores' => [$storeTransfer->getIdStore()]],
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
        $productOfferStockRequestTransfer = new ProductOfferStockRequestTransfer();
        $productOfferStockRequestTransfer->setProductOfferReference($productOffer->getProductOfferReference());
        $productOfferStockRequestTransfer->setStore((new StoreTransfer())->setIdStore($stockTransfer->getStoreRelation()->getIdStores()[0]));

        $response = $this->tester->getFacade()->isProductOfferNeverOutOfStock($productOfferStockRequestTransfer);

        // Assert
        $this->assertTrue($response);
    }
}
