<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
    public function testGetProductOfferStockResultSetIsNeverOutOfStockIfAtLeastOneProductOfferStockIsNeverOutOfStock(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();

        $stockTransfer1 = $this->tester->haveStock([
            StockTransfer::STORE_RELATION => [StoreRelationTransfer::ID_STORES => [$storeTransfer->getIdStore()]],
        ]);
        $stockTransfer2 = $this->tester->haveStock([
            StockTransfer::STORE_RELATION => [StoreRelationTransfer::ID_STORES => [$storeTransfer->getIdStore()]],
        ]);

        $productOfferTransfer = $this->tester->haveProductOffer();
        $this->tester->haveProductOfferStock([
            ProductOfferStockTransfer::ID_PRODUCT_OFFER => $productOfferTransfer->getIdProductOffer(),
            ProductOfferStockTransfer::STOCK => $stockTransfer1->toArray(),
            ProductOfferStockTransfer::IS_NEVER_OUT_OF_STOCK => true,
        ]);
        $this->tester->haveProductOfferStock([
            ProductOfferStockTransfer::ID_PRODUCT_OFFER => $productOfferTransfer->getIdProductOffer(),
            ProductOfferStockTransfer::STOCK => $stockTransfer2->toArray(),
            ProductOfferStockTransfer::IS_NEVER_OUT_OF_STOCK => false,
        ]);

        // Act
        $productOfferStockRequestTransfer = new ProductOfferStockRequestTransfer();
        $productOfferStockRequestTransfer->setProductOfferReference($productOfferTransfer->getProductOfferReference());
        $productOfferStockRequestTransfer->setStore($storeTransfer);

        $response = $this->tester->getFacade()->getProductOfferStockResult($productOfferStockRequestTransfer)->getIsNeverOutOfStock();

        // Assert
        $this->assertTrue($response);
    }

    /**
     * @return void
     */
    public function testGetProductOfferStockResultReturnsSummarizedStockQuantity(): void
    {
        // Arrange
        $stockQuantity1 = 5;
        $stockQuantity2 = 6;
        $expectedResult = $stockQuantity1 + $stockQuantity2;

        $storeTransfer = $this->tester->haveStore();
        $productOfferTransfer = $this->tester->haveProductOffer();

        $this->tester->haveProductOfferStock([
            ProductOfferStockTransfer::ID_PRODUCT_OFFER => $productOfferTransfer->getIdProductOffer(),
            ProductOfferStockTransfer::QUANTITY => $stockQuantity1,
            ProductOfferStockTransfer::STOCK => [
                StockTransfer::STORE_RELATION => [
                    StoreRelationTransfer::ID_STORES => [
                        $storeTransfer->getIdStore(),
                    ],
                ],
            ],
        ]);
        $this->tester->haveProductOfferStock([
            ProductOfferStockTransfer::ID_PRODUCT_OFFER => $productOfferTransfer->getIdProductOffer(),
            ProductOfferStockTransfer::QUANTITY => $stockQuantity2,
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
            ->getProductOfferStockResult($productOfferStockRequestTransfer)->getQuantity();

        // Assert
        $this->assertSame($expectedResult, $productOfferStockTransfer->toInt());
    }

    /**
     * @return void
     */
    public function testGetProductOfferStockResultShouldReturnQuantityOfZeroIfStockIsInactive(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $productOfferTransfer = $this->tester->haveProductOffer();
        $productOfferStockTransfer = $this->tester->haveProductOfferStock([
            ProductOfferStockTransfer::ID_PRODUCT_OFFER => $productOfferTransfer->getIdProductOffer(),
            ProductOfferStockTransfer::QUANTITY => 6,
            ProductOfferStockTransfer::STOCK => [
                StockTransfer::STORE_RELATION => [
                    StoreRelationTransfer::ID_STORES => [
                        $storeTransfer->getIdStore(),
                    ],
                ],
            ],
        ]);
        $this->tester->updateStock($productOfferStockTransfer->getStock()->setIsActive(false));

        $productOfferStockRequestTransfer = (new ProductOfferStockRequestTransfer())
            ->setProductOfferReference($productOfferTransfer->getProductOfferReference())
            ->setIsStockActive(true)
            ->setStore($storeTransfer);

        // Act
        $productOfferStockTransfer = $this->tester->getFacade()
            ->getProductOfferStockResult($productOfferStockRequestTransfer)->getQuantity();

        // Assert
        $this->assertSame(0, $productOfferStockTransfer->toInt());
    }

    /**
     * @return void
     */
    public function testGetProductOfferStockResultReturnsZeroQuantityIfProductOfferNotExists(): void
    {
        // Arrange
        $notExistingProductOfferReference = 'not-existing-product-offer-reference';

        $storeTransfer = $this->tester->haveStore();
        $productOfferStockRequestTransfer = (new ProductOfferStockRequestTransfer())
            ->setProductOfferReference($notExistingProductOfferReference)
            ->setStore($storeTransfer);

        // Act
        $productOfferStockTransfer = $this->tester->getFacade()
            ->getProductOfferStockResult($productOfferStockRequestTransfer);

        // Assert
        $this->assertSame(0, $productOfferStockTransfer->getQuantity()->toInt());
    }

    /**
     * @return void
     */
    public function testGetProductOfferStocksReturnsCorrectAmountOfStocks(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $productOfferTransfer = $this->tester->haveProductOffer();
        $stocksAmount = 5;
        for ($i = 1; $i <= $stocksAmount; $i++) {
            $this->tester->haveProductOfferStock([
                ProductOfferStockTransfer::ID_PRODUCT_OFFER => $productOfferTransfer->getIdProductOffer(),
                ProductOfferStockTransfer::STOCK => [
                    StockTransfer::STORE_RELATION => [
                        StoreRelationTransfer::ID_STORES => [
                            $storeTransfer->getIdStore(),
                        ],
                    ],
                ],
            ]);
        }

        $productOfferStockRequestTransfer = (new ProductOfferStockRequestTransfer())
            ->setProductOfferReference($productOfferTransfer->getProductOfferReference())
            ->setStore($storeTransfer);

        // Act
        $productOfferStockTransfers = $this->tester->getFacade()
            ->getProductOfferStocks($productOfferStockRequestTransfer);

        // Assert
        $this->assertSame($stocksAmount, $productOfferStockTransfers->count());
    }

    /**
     * @return void
     */
    public function testGetProductOfferStocksReturnsNullIfProductOfferNotExists(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $productOfferStockRequestTransfer = (new ProductOfferStockRequestTransfer())
            ->setProductOfferReference('not-existing-product-offer-reference')
            ->setStore($storeTransfer);

        // Assert
        $this->expectException(ProductOfferNotFoundException::class);

        // Act
        $productOfferStockTransfers = $this->tester->getFacade()
            ->getProductOfferStocks($productOfferStockRequestTransfer);
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
            (new ProductOfferTransfer())->setProductOfferReference($productOfferTransfer->getProductOfferReference()),
        )->getProductOfferStocks();
        $productOfferStockTransferFromDb = $this->tester->getProductOfferStockRepository()->findOne($productOfferStockRequestTransfer);

        // Assert
        $this->assertEquals($productOfferStockTransfers->offsetGet(0)->getIsNeverOutOfStock(), $productOfferStockTransferFromDb->getIsNeverOutOfStock());
        $this->assertEquals($productOfferStockTransfers->offsetGet(0)->getQuantity()->toInt(), $productOfferStockTransferFromDb->getQuantity()->toInt());
        $this->assertEquals($productOfferStockTransfers->offsetGet(0)->getStock()->getIdStock(), $productOfferStockTransferFromDb->getStock()->getIdStock());
    }
}
