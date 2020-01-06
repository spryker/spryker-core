<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOfferStock\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductOfferStockRequestTransfer;
use Generated\Shared\Transfer\ProductOfferStockTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
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
        $storeTransfer = $this->tester->haveStore();

        $stockTransfer = $this->tester->haveStock([
            StockTransfer::STORE_RELATION => ['idStores' => [$storeTransfer->getIdStore()]],
        ]);

        $productOfferStock = $this->tester->haveProductOfferStock([
            ProductOfferStockTransfer::FK_STOCK => $stockTransfer->getIdStock(),
            ProductOfferStockTransfer::IS_NEVER_OUT_OF_STOCK => true,
        ]);

        $this->tester->haveStockStoreRelation($productOfferStock->getStock(), $storeTransfer);

        // Act
        $productOfferStockRequestTransfer = new ProductOfferStockRequestTransfer();
        $productOfferStockRequestTransfer->setProductOfferReference($productOfferStock->getProductOffer()->getProductOfferReference());
        $productOfferStockRequestTransfer->setStore((new StoreTransfer())->setIdStore($stockTransfer->getStoreRelation()->getIdStores()[0]));

        $response = $this->tester->getFacade()->isProductOfferNeverOutOfStock($productOfferStockRequestTransfer);

        // Assert
        $this->assertTrue($response);
    }

    /**
     * @return void
     */
    public function testGetProductOfferStockReturnsAvailableStockAmount(): void
    {
        // Arrange
        $stockQuantity = 5;
        $expectedResult = $stockQuantity;

        $storeTransfer = $this->tester->haveStore();
        $productOfferStockTransfer = $this->tester->haveProductOfferStock([
            ProductOfferStockTransfer::QUANTITY => $stockQuantity,
            ProductOfferStockTransfer::STOCK => [
                StockTransfer::STORE_RELATION => [
                    StoreRelationTransfer::ID_STORES => [
                        $storeTransfer->getIdStore(),
                    ],
                ],
            ],
        ]);

        $productOfferStockRequestTransfer = (new ProductOfferStockRequestTransfer())
            ->setProductOfferReference($productOfferStockTransfer->getProductOffer()->getProductOfferReference())
            ->setStore($storeTransfer);

        // Act
        $productOfferStock = $this->tester->getFacade()
            ->getProductOfferStock($productOfferStockRequestTransfer);

        // Assert
        $this->assertSame($expectedResult, $productOfferStock->toInt());
    }

    /**
     * @return void
     */
    public function testGetProductOfferStockReturnsNothingIfProductOfferNotExists(): void
    {
        // Arrange
        $notExistingProductOfferReference = 'not-existing-product-offer-reference';

        $storeTransfer = $this->tester->haveStore();
        $productOfferStockRequestTransfer = (new ProductOfferStockRequestTransfer())
            ->setProductOfferReference($notExistingProductOfferReference)
            ->setStore($storeTransfer);

        // Act
        $productOfferStock = $this->tester->getFacade()
            ->getProductOfferStock($productOfferStockRequestTransfer);

        // Assert
        $this->assertSame(0, $productOfferStock->toInt());
    }
}
