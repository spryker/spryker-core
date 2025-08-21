<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProduct\Business\Facade;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\PriceTypeTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use SprykerTest\Zed\PriceProduct\PriceProductBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PriceProduct
 * @group Business
 * @group Facade
 * @group FindProductConcretePricesWithoutPriceExtractionByConcreteAbstractMapTest
 * Add your own group annotations below this line
 */
class FindProductConcretePricesWithoutPriceExtractionByConcreteAbstractMapTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\PriceProduct\PriceProductBusinessTester
     */
    protected PriceProductBusinessTester $tester;

    /**
     * @return void
     */
    public function testFindProductConcretePricesWithoutPriceExtractionByConcreteAbstractMapShouldReturnPricesGroupedByConcreteProductId(): void
    {
        // Arrange
        $priceProductFacade = $this->tester->getFacade();

        $priceTypeTransfer = new PriceTypeTransfer();
        $priceTypeTransfer->setName($priceProductFacade->getDefaultPriceTypeName());

        $productConcreteTransfer1 = $this->tester->haveProduct();
        $prices1 = new ArrayObject();
        $prices1[] = $this->tester->createPriceProductTransfer($productConcreteTransfer1, $priceTypeTransfer, 100, 90, PriceProductBusinessTester::EUR_ISO_CODE);
        $productConcreteTransfer1->setPrices($prices1);
        $productConcreteTransfer1 = $priceProductFacade->persistProductConcretePriceCollection($productConcreteTransfer1);

        $productConcreteTransfer2 = $this->tester->haveProduct();
        $prices2 = new ArrayObject();
        $prices2[] = $this->tester->createPriceProductTransfer($productConcreteTransfer2, $priceTypeTransfer, 200, 180, PriceProductBusinessTester::EUR_ISO_CODE);
        $productConcreteTransfer2->setPrices($prices2);
        $productConcreteTransfer2 = $priceProductFacade->persistProductConcretePriceCollection($productConcreteTransfer2);

        $productAbstractIdMap = [
            $productConcreteTransfer1->getIdProductConcrete() => $productConcreteTransfer1->getFkProductAbstract(),
            $productConcreteTransfer2->getIdProductConcrete() => $productConcreteTransfer2->getFkProductAbstract(),
        ];

        $priceProductCriteriaTransfer = $this->tester->createPriceProductCriteriaTransfer();
        $priceProductCriteriaTransfer->setProductConcreteToAbstractIdMaps($productAbstractIdMap);

        // Act
        $result = $priceProductFacade->findProductConcretePricesWithoutPriceExtractionByConcreteAbstractMap($priceProductCriteriaTransfer);

        // Assert
        $this->assertIsArray($result);
        $this->assertCount(2, $result);
        $this->assertArrayHasKey($productConcreteTransfer1->getIdProductConcrete(), $result);
        $this->assertArrayHasKey($productConcreteTransfer2->getIdProductConcrete(), $result);

        $prices1Result = $result[$productConcreteTransfer1->getIdProductConcrete()];
        $this->assertIsArray($prices1Result);
        $this->assertNotEmpty($prices1Result);

        $prices2Result = $result[$productConcreteTransfer2->getIdProductConcrete()];
        $this->assertIsArray($prices2Result);
        $this->assertNotEmpty($prices2Result);
    }

    /**
     * @return void
     */
    public function testFindProductConcretePricesWithoutPriceExtractionByConcreteAbstractMapShouldReturnEmptyArrayForEmptyInput(): void
    {
        // Arrange
        $priceProductFacade = $this->tester->getFacade();
        $productAbstractIdMap = [];
        $priceProductCriteriaTransfer = $this->tester->createPriceProductCriteriaTransfer();
        $priceProductCriteriaTransfer->setProductConcreteToAbstractIdMaps($productAbstractIdMap);

        // Act
        $result = $priceProductFacade->findProductConcretePricesWithoutPriceExtractionByConcreteAbstractMap($priceProductCriteriaTransfer);

        // Assert
        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    /**
     * @return void
     */
    public function testFindProductConcretePricesWithoutPriceExtractionByConcreteAbstractMapShouldReturnEmptyPricesForNonExistentProducts(): void
    {
        // Arrange
        $priceProductFacade = $this->tester->getFacade();
        $nonExistentProductId1 = 99999;
        $nonExistentProductId2 = 99998;
        $productAbstractIdMap = [
            $nonExistentProductId1 => 88888,
            $nonExistentProductId2 => 88887,
        ];
        $priceProductCriteriaTransfer = $this->tester->createPriceProductCriteriaTransfer();
        $priceProductCriteriaTransfer->setProductConcreteToAbstractIdMaps($productAbstractIdMap);

        // Act
        $result = $priceProductFacade->findProductConcretePricesWithoutPriceExtractionByConcreteAbstractMap($priceProductCriteriaTransfer);

        // Assert
        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    /**
     * @return void
     */
    public function testFindProductConcretePricesWithoutPriceExtractionByConcreteAbstractMapShouldRespectPriceProductCriteria(): void
    {
        // Arrange
        $priceProductFacade = $this->tester->getFacade();

        $priceTypeTransfer = new PriceTypeTransfer();
        $priceTypeTransfer->setName($priceProductFacade->getDefaultPriceTypeName());

        // Create product with prices in multiple stores
        $productConcreteTransfer = $this->tester->haveProduct();
        $prices = new ArrayObject();
        $prices[] = $this->tester->createPriceProductTransfer($productConcreteTransfer, $priceTypeTransfer, 100, 90, PriceProductBusinessTester::EUR_ISO_CODE);
        $prices[] = $this->tester->createPriceProductTransfer($productConcreteTransfer, $priceTypeTransfer, 120, 110, PriceProductBusinessTester::USD_ISO_CODE);
        $productConcreteTransfer->setPrices($prices);
        $productConcreteTransfer = $priceProductFacade->persistProductConcretePriceCollection($productConcreteTransfer);

        $productAbstractIdMap = [
            $productConcreteTransfer->getIdProductConcrete() => $productConcreteTransfer->getFkProductAbstract(),
        ];
        $priceProductCriteriaTransfer = $this->tester->createPriceProductCriteriaTransfer();
        $priceProductCriteriaTransfer->setProductConcreteToAbstractIdMaps($productAbstractIdMap);

        // Act
        $result = $priceProductFacade->findProductConcretePricesWithoutPriceExtractionByConcreteAbstractMap($priceProductCriteriaTransfer);

        // Assert
        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey($productConcreteTransfer->getIdProductConcrete(), $result);

        $pricesResult = $result[$productConcreteTransfer->getIdProductConcrete()];
        $this->assertIsArray($pricesResult);
    }

    /**
     * @return void
     */
    public function testFindProductConcretePricesWithoutPriceExtractionByConcreteAbstractMapShouldIncludeAbstractPricesWhenConcreteHasNone(): void
    {
        // Arrange
        $priceProductFacade = $this->tester->getFacade();

        $priceTypeTransfer = new PriceTypeTransfer();
        $priceTypeTransfer->setName($priceProductFacade->getDefaultPriceTypeName());

        // Create product abstract with prices
        $productAbstractTransfer = $this->tester->haveProductAbstract();
        $abstractPrices = new ArrayObject();
        $abstractPrices[] = $this->tester->havePriceProductAbstract(
            $productAbstractTransfer->getIdProductAbstractOrFail(),
            [
                PriceProductTransfer::SKU_PRODUCT_ABSTRACT => $productAbstractTransfer->getSku(),
            ],
        );
        $productAbstractTransfer->setPrices($abstractPrices);
        $productAbstractTransfer = $priceProductFacade->persistProductAbstractPriceCollection($productAbstractTransfer);

        // Create product concrete without prices (will inherit from abstract)
        $productConcreteTransfer = $this->tester->haveProduct([ProductConcreteTransfer::FK_PRODUCT_ABSTRACT => $productAbstractTransfer->getIdProductAbstract()]);

        $productAbstractIdMap = [
            $productConcreteTransfer->getIdProductConcrete() => $productAbstractTransfer->getIdProductAbstract(),
        ];

        $priceProductCriteriaTransfer = $this->tester->createPriceProductCriteriaTransfer();
        $priceProductCriteriaTransfer->setOnlyConcretePrices(false);
        $priceProductCriteriaTransfer->setProductConcreteToAbstractIdMaps($productAbstractIdMap);

        // Act
        $result = $priceProductFacade->findProductConcretePricesWithoutPriceExtractionByConcreteAbstractMap($priceProductCriteriaTransfer);

        // Assert
        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey($productConcreteTransfer->getIdProductConcrete(), $result);

        $pricesResult = $result[$productConcreteTransfer->getIdProductConcrete()];
        $this->assertIsArray($pricesResult);
        $this->assertNotEmpty($pricesResult);

        // Verify that abstract prices are returned for concrete product
        foreach ($pricesResult as $priceProductTransfer) {
            $this->assertEquals($productAbstractTransfer->getIdProductAbstract(), $priceProductTransfer->getIdProductAbstract());
            $this->assertNull($priceProductTransfer->getIdProduct());
        }
    }

    /**
     * @return void
     */
    public function testFindProductConcretePricesWithoutPriceExtractionByConcreteAbstractMapShouldOnlyReturnConcretePricesWhenCriteriaSpecifies(): void
    {
        // Arrange
        $priceProductFacade = $this->tester->getFacade();

        $priceTypeTransfer = new PriceTypeTransfer();
        $priceTypeTransfer->setName($priceProductFacade->getDefaultPriceTypeName());

        // Create product abstract with prices
        $productAbstractTransfer = $this->tester->haveProductAbstract();
        $abstractPrices = new ArrayObject();
        $abstractPrices[] = $this->tester->havePriceProductAbstract(
            $productAbstractTransfer->getIdProductAbstractOrFail(),
            [
                PriceProductTransfer::SKU_PRODUCT_ABSTRACT => $productAbstractTransfer->getSku(),
            ],
        );

        // Create product concrete with prices
        $productConcreteTransfer = $this->tester->haveProduct([ProductConcreteTransfer::FK_PRODUCT_ABSTRACT => $productAbstractTransfer->getIdProductAbstract()]);
        $concretePrices = new ArrayObject();
        $concretePrices[] = $this->tester->createPriceProductTransfer($productConcreteTransfer, $priceTypeTransfer, 100, 90, PriceProductBusinessTester::EUR_ISO_CODE);
        $productConcreteTransfer->setPrices($concretePrices);
        $productConcreteTransfer = $priceProductFacade->persistProductConcretePriceCollection($productConcreteTransfer);

        $productAbstractIdMap = [
            $productConcreteTransfer->getIdProductConcrete() => $productConcreteTransfer->getFkProductAbstract(),
        ];

        // Create criteria that specifies only concrete prices
        $priceProductCriteriaTransfer = $this->tester->createPriceProductCriteriaTransfer();
        $priceProductCriteriaTransfer->setOnlyConcretePrices(true);
        $priceProductCriteriaTransfer->setProductConcreteToAbstractIdMaps($productAbstractIdMap);

        // Act
        $result = $priceProductFacade->findProductConcretePricesWithoutPriceExtractionByConcreteAbstractMap($priceProductCriteriaTransfer);

        // Assert
        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey($productConcreteTransfer->getIdProductConcrete(), $result);

        $pricesResult = $result[$productConcreteTransfer->getIdProductConcrete()];
        $this->assertIsArray($pricesResult);
        $this->assertNotEmpty($pricesResult);

        // Verify that only concrete prices are returned
        foreach ($pricesResult as $priceProductTransfer) {
            $this->assertEquals($productConcreteTransfer->getIdProductConcrete(), $priceProductTransfer->getIdProduct());
            $this->assertNotNull($priceProductTransfer->getIdProduct());
        }
    }

    /**
     * @return void
     */
    public function testFindProductConcretePricesWithoutPriceExtractionByConcreteAbstractMapShouldHandleLargeProductMap(): void
    {
        // Arrange
        $priceProductFacade = $this->tester->getFacade();

        $priceTypeTransfer = new PriceTypeTransfer();
        $priceTypeTransfer->setName($priceProductFacade->getDefaultPriceTypeName());

        $productAbstractIdMap = [];
        $expectedProductIds = [];

        // Create multiple products with prices
        for ($i = 0; $i < 5; $i++) {
            $productConcreteTransfer = $this->tester->haveProduct();
            $prices = new ArrayObject();
            $prices[] = $this->tester->createPriceProductTransfer($productConcreteTransfer, $priceTypeTransfer, 100 + $i * 10, 90 + $i * 10, PriceProductBusinessTester::EUR_ISO_CODE);
            $productConcreteTransfer->setPrices($prices);
            $productConcreteTransfer = $priceProductFacade->persistProductConcretePriceCollection($productConcreteTransfer);

            $productAbstractIdMap[$productConcreteTransfer->getIdProductConcrete()] = $productConcreteTransfer->getFkProductAbstract();
            $expectedProductIds[] = $productConcreteTransfer->getIdProductConcrete();
        }

        $priceProductCriteriaTransfer = $this->tester->createPriceProductCriteriaTransfer();
        $priceProductCriteriaTransfer->setProductConcreteToAbstractIdMaps($productAbstractIdMap);

        // Act
        $result = $priceProductFacade->findProductConcretePricesWithoutPriceExtractionByConcreteAbstractMap($priceProductCriteriaTransfer);

        // Assert
        $this->assertIsArray($result);
        $this->assertCount(5, $result);

        foreach ($expectedProductIds as $productId) {
            $this->assertArrayHasKey($productId, $result);
            $this->assertIsArray($result[$productId]);
            $this->assertNotEmpty($result[$productId]);
        }
    }

    /**
     * @return void
     */
    public function testFindProductConcretePricesWithoutPriceExtractionByConcreteAbstractMapShouldWorkWithDifferentPriceDimensions(): void
    {
        // Arrange
        $priceProductFacade = $this->tester->getFacade();

        $priceTypeTransfer = new PriceTypeTransfer();
        $priceTypeTransfer->setName($priceProductFacade->getDefaultPriceTypeName());

        $productConcreteTransfer = $this->tester->haveProduct();
        $prices = new ArrayObject();
        $prices[] = $this->tester->createPriceProductTransfer($productConcreteTransfer, $priceTypeTransfer, 100, 90, PriceProductBusinessTester::EUR_ISO_CODE);
        $productConcreteTransfer->setPrices($prices);
        $productConcreteTransfer = $priceProductFacade->persistProductConcretePriceCollection($productConcreteTransfer);

        $productAbstractIdMap = [
            $productConcreteTransfer->getIdProductConcrete() => $productConcreteTransfer->getFkProductAbstract(),
        ];

        $priceProductCriteriaTransfer = $this->tester->createPriceProductCriteriaTransfer();
        $priceProductCriteriaTransfer->setProductConcreteToAbstractIdMaps($productAbstractIdMap);
        // Act
        $result = $priceProductFacade->findProductConcretePricesWithoutPriceExtractionByConcreteAbstractMap($priceProductCriteriaTransfer);

        // Assert
        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey($productConcreteTransfer->getIdProductConcrete(), $result);

        $pricesResult = $result[$productConcreteTransfer->getIdProductConcrete()];
        $this->assertIsArray($pricesResult);

        $priceDimension = $this->tester->createSharedPriceProductConfig()->getPriceDimensionDefault();
        foreach ($pricesResult as $priceProductTransfer) {
            $this->assertEquals($priceDimension, $priceProductTransfer->getPriceDimension()->getType());
        }
    }
}
