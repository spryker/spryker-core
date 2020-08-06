<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOfferStock\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductOfferStockRequestTransfer;
use Generated\Shared\Transfer\ProductOfferStockTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Spryker\Zed\ProductOfferStock\Business\Exception\ProductOfferNotFoundException;
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
            StockTransfer::STORE_RELATION => [StoreRelationTransfer::ID_STORES => [$storeTransfer->getIdStore()]],
        ]);

        $productOfferTransfer = $this->tester->haveProductOffer();
        $this->tester->haveProductOfferStock([
            ProductOfferStockTransfer::ID_PRODUCT_OFFER => $productOfferTransfer->getIdProductOffer(),
            ProductOfferStockTransfer::STOCK => $stockTransfer->toArray(),
            ProductOfferStockTransfer::IS_NEVER_OUT_OF_STOCK => true,
        ]);

        // Act
        $productOfferStockRequestTransfer = new ProductOfferStockRequestTransfer();
        $productOfferStockRequestTransfer->setProductOfferReference($productOfferTransfer->getProductOfferReference());
        $productOfferStockRequestTransfer->setStore($storeTransfer);

        $response = $this->tester->getFacade()->getProductOfferStock($productOfferStockRequestTransfer)->getIsNeverOutOfStock();

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
        $productOfferTransfer = $this->tester->haveProductOffer();
        $this->tester->haveProductOfferStock([
            ProductOfferStockTransfer::ID_PRODUCT_OFFER => $productOfferTransfer->getIdProductOffer(),
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
            ->setProductOfferReference($productOfferTransfer->getProductOfferReference())
            ->setStore($storeTransfer);

        // Act
        $productOfferStockTransfer = $this->tester->getFacade()
            ->getProductOfferStock($productOfferStockRequestTransfer)->getQuantity();

        // Assert
        $this->assertSame($expectedResult, $productOfferStockTransfer->toInt());
    }

    /**
     * @return void
     */
    public function testGetProductOfferStockReturnsNothingIfProductOfferNotExists(): void
    {
        // Arrange
        $notExistingProductOfferReference = 'not-existing-product-offer-reference';

        $this->expectException(ProductOfferNotFoundException::class);

        $storeTransfer = $this->tester->haveStore();
        $productOfferStockRequestTransfer = (new ProductOfferStockRequestTransfer())
            ->setProductOfferReference($notExistingProductOfferReference)
            ->setStore($storeTransfer);

        // Act
        $productOfferStockTransfer = $this->tester->getFacade()
            ->getProductOfferStock($productOfferStockRequestTransfer);

        // Assert
        $this->assertNull($productOfferStockTransfer);
    }

    /**
     * @return void
     */
    public function testCreatePersistsNewEntityToDatabase(): void
    {
        // Arrange
        $productOfferTransfer = $this->tester->haveProductOffer();
        $productOfferStockTransfer = (new ProductOfferStockTransfer())
            ->setStock($this->tester->haveStock())
            ->setIdProductOffer($productOfferTransfer->getIdProductOffer())
            ->setQuantity(5)
            ->setIsNeverOutOfStock(true);
        $productOfferStockRequestTransfer = (new ProductOfferStockRequestTransfer())
            ->setProductOfferReference($productOfferTransfer->getProductOfferReference());

        // Act
        $this->tester->getFacade()->create($productOfferStockTransfer);
        $productOfferStockTransferFromDb = $this->tester->getProductOfferStockRepository()->findOne($productOfferStockRequestTransfer);

        // Assert
        $this->assertEquals($productOfferStockTransfer->getIsNeverOutOfStock(), $productOfferStockTransferFromDb->getIsNeverOutOfStock());
        $this->assertEquals($productOfferStockTransfer->getQuantity()->toInt(), $productOfferStockTransferFromDb->getQuantity()->toInt());
        $this->assertEquals($productOfferStockTransfer->getStock()->getIdStock(), $productOfferStockTransferFromDb->getStock()->getIdStock());
    }

    /**
     * @return void
     */
    public function testUpdateUpdatesProductOfferStock(): void
    {
        // Arrange
        $productOfferTransfer = $this->tester->haveProductOffer();
        $productOfferStockTransfer = $this->tester->haveProductOfferStock([
            ProductOfferStockTransfer::ID_PRODUCT_OFFER => $productOfferTransfer->getIdProductOffer(),
        ]);
        $productOfferStockTransfer->setIdProductOffer($productOfferTransfer->getIdProductOffer());
        $productOfferStockTransfer->setQuantity($productOfferStockTransfer->getQuantity()->toInt() + 1);
        $productOfferStockTransfer->setIsNeverOutOfStock(!$productOfferStockTransfer->getIsNeverOutOfStock());
        $productOfferStockTransfer->setStock($this->tester->haveStock());

        $productOfferStockRequestTransfer = (new ProductOfferStockRequestTransfer())
            ->setProductOfferReference($productOfferTransfer->getProductOfferReference());

        // Act
        $this->tester->getFacade()->update($productOfferStockTransfer);
        $productOfferStockTransferFromDb = $this->tester->getProductOfferStockRepository()->findOne($productOfferStockRequestTransfer);

        // Assert
        $this->assertEquals($productOfferStockTransfer->getIsNeverOutOfStock(), $productOfferStockTransferFromDb->getIsNeverOutOfStock());
        $this->assertEquals($productOfferStockTransfer->getQuantity()->toInt(), $productOfferStockTransferFromDb->getQuantity()->toInt());
        $this->assertEquals($productOfferStockTransfer->getStock()->getIdStock(), $productOfferStockTransferFromDb->getStock()->getIdStock());
    }

    /**
     * @return void
     */
    public function testExpandProductOfferWithProductOfferStockCollectionExpandsProductOffer(): void
    {
        $productOfferTransfer = $this->tester->haveProductOffer();
        $this->tester->haveProductOfferStock([
            ProductOfferStockTransfer::ID_PRODUCT_OFFER => $productOfferTransfer->getIdProductOffer(),
        ]);
        $productOfferStockRequestTransfer = (new ProductOfferStockRequestTransfer())
            ->setProductOfferReference($productOfferTransfer->getProductOfferReference());

        // Act
        $productOfferStockTransfers = $this->tester->getFacade()->expandProductOfferWithProductOfferStockCollection(
            (new ProductOfferTransfer())->setProductOfferReference($productOfferTransfer->getProductOfferReference())
        )->getProductOfferStocks();
        $productOfferStockTransferFromDb = $this->tester->getProductOfferStockRepository()->findOne($productOfferStockRequestTransfer);

        // Assert
        $this->assertEquals($productOfferStockTransfers->offsetGet(0)->getIsNeverOutOfStock(), $productOfferStockTransferFromDb->getIsNeverOutOfStock());
        $this->assertEquals($productOfferStockTransfers->offsetGet(0)->getQuantity()->toInt(), $productOfferStockTransferFromDb->getQuantity()->toInt());
        $this->assertEquals($productOfferStockTransfers->offsetGet(0)->getStock()->getIdStock(), $productOfferStockTransferFromDb->getStock()->getIdStock());
    }
}
