<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductList\Persistence;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductListProductConcreteRelationTransfer;
use Generated\Shared\Transfer\ProductListTransfer;
use Orm\Zed\ProductList\Persistence\Map\SpyProductListProductConcreteTableMap;
use Orm\Zed\ProductList\Persistence\Map\SpyProductListTableMap;
use Spryker\Zed\ProductList\Persistence\ProductListRepository;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductList
 * @group Persistence
 * @group ProductListRepositoryTest
 * Add your own group annotations below this line
 */
class ProductListRepositoryTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductList\ProductListPersistenceTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\ProductList\Persistence\ProductListRepositoryInterface
     */
    protected $productListRepository;

    /**
     * @var \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected $productConcreteTransfer1;

    /**
     * @var \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected $productConcreteTransfer2;

    /**
     * @var \Generated\Shared\Transfer\ProductListProductConcreteRelationTransfer
     */
    protected $productListProductConcreteRelation;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->productListRepository = new ProductListRepository();
        $this->productConcreteTransfer1 = $this->tester->haveProduct();
        $this->productConcreteTransfer2 = $this->tester->haveProduct();

        $this->productListProductConcreteRelation = (new ProductListProductConcreteRelationTransfer())
            ->setProductIds([
                $this->productConcreteTransfer1->getIdProductConcrete(),
                $this->productConcreteTransfer2->getIdProductConcrete(),
            ]);
    }

    /**
     * @return void
     */
    public function testGetProductListIdsByProductIdsReturnsCorrectData(): void
    {
        //Assert
        $productConcreteIds = [
            $this->productConcreteTransfer1->getIdProductConcrete(),
            $this->productConcreteTransfer2->getIdProductConcrete(),
        ];
        $this->tester->haveProductList([
            ProductListTransfer::PRODUCT_LIST_PRODUCT_CONCRETE_RELATION => $this->productListProductConcreteRelation->toArray(),
        ]);

        //Act
        $result = $this->productListRepository->getProductListIdsByProductIds($productConcreteIds);

        //Arrange
        $this->assertCount(2, $result);
        $resultProductConcreteIds = array_map(
            'intval',
            array_column($result, SpyProductListProductConcreteTableMap::COL_FK_PRODUCT)
        );
        $this->assertContains($productConcreteIds[0], $resultProductConcreteIds);
        $this->assertContains($productConcreteIds[1], $resultProductConcreteIds);
    }

    /**
     * @return void
     */
    public function testGetProductConcreteProductListIdsForTypeReturnsCorrectProductListId(): void
    {
        //Assert
        $productListTransfer = $this->tester->haveProductList([
            ProductListTransfer::PRODUCT_LIST_PRODUCT_CONCRETE_RELATION => $this->productListProductConcreteRelation->toArray(),
        ]);

        //Act
        $result = $this->productListRepository->getProductConcreteProductListIdsForType(
            $this->productConcreteTransfer1->getIdProductConcrete(),
            $productListTransfer->getType()
        );

        //Arrange
        $this->assertCount(1, $result);
        $this->assertEquals($productListTransfer->getIdProductList(), $result[0]);
    }

    /**
     * @return void
     */
    public function testGetProductBlacklistIdsByIdProductAbstractReturnsCorrectProductListId(): void
    {
        //Assert
        $productListTransfer = $this->tester->haveProductList([
            ProductListTransfer::TYPE => SpyProductListTableMap::COL_TYPE_BLACKLIST,
            ProductListTransfer::PRODUCT_LIST_PRODUCT_CONCRETE_RELATION => $this->productListProductConcreteRelation->toArray(),
        ]);

        //Act
        $result = $this->productListRepository->getProductBlacklistIdsByIdProductAbstract(
            $this->productConcreteTransfer1->getFkProductAbstract()
        );

        //Arrange
        $this->assertCount(1, $result);
        $this->assertEquals($productListTransfer->getIdProductList(), $result[0]);
    }

    /**
     * @return void
     */
    public function testGetAbstractProductWhitelistIdsReturnsCorrectProductListId(): void
    {
        //Assert
        $productListTransfer = $this->tester->haveProductList([
            ProductListTransfer::TYPE => SpyProductListTableMap::COL_TYPE_WHITELIST,
            ProductListTransfer::PRODUCT_LIST_PRODUCT_CONCRETE_RELATION => $this->productListProductConcreteRelation->toArray(),
        ]);

        //Act
        $result = $this->productListRepository->getAbstractProductWhitelistIds(
            $this->productConcreteTransfer1->getFkProductAbstract()
        );

        //Arrange
        $this->assertCount(1, $result);
        $this->assertEquals($productListTransfer->getIdProductList(), $result[0]);
    }

    /**
     * @return void
     */
    public function testGetProductListByProductAbstractIdsThroughCategoryReturnsCorrectProductAbstractIds(): void
    {
        //Assert
        $categoryTransfer = $this->tester->haveCategory();
        $this->tester->assignProductToCategory($categoryTransfer->getIdCategory(), $this->productConcreteTransfer1->getFkProductAbstract());
        $this->tester->assignProductToCategory($categoryTransfer->getIdCategory(), $this->productConcreteTransfer2->getFkProductAbstract());

        $productListTransfer = $this->tester->haveProductList([
            ProductListTransfer::TYPE => SpyProductListTableMap::COL_TYPE_WHITELIST,
            ProductListTransfer::PRODUCT_LIST_PRODUCT_CONCRETE_RELATION => $this->productListProductConcreteRelation->toArray(),
        ]);
        $this->tester->haveProductListCategory($productListTransfer->getIdProductList(), $categoryTransfer->getIdCategory());

        $productAbstractIds = [
            $this->productConcreteTransfer1->getFkProductAbstract(),
            $this->productConcreteTransfer2->getFkProductAbstract(),
        ];

        //Act
        $result = $this->productListRepository->getProductListByProductAbstractIdsThroughCategory(
            $productAbstractIds
        );

        //Arrange
        $this->assertCount(2, $result);
        $resultProductAbstractIds = array_map(
            'intval',
            array_column($result, ProductListRepository::COL_ID_PRODUCT_ABSTRACT)
        );
        $this->assertContains($productAbstractIds[0], $resultProductAbstractIds);
        $this->assertContains($productAbstractIds[1], $resultProductAbstractIds);
    }

    /**
     * @return void
     */
    public function testGetProductBlacklistsByProductAbstractIdsReturnsCorrectData(): void
    {
        //Assert
        $this->tester->haveProductList([
            ProductListTransfer::TYPE => SpyProductListTableMap::COL_TYPE_BLACKLIST,
            ProductListTransfer::PRODUCT_LIST_PRODUCT_CONCRETE_RELATION => $this->productListProductConcreteRelation->toArray(),
        ]);

        $productAbstractIds = [
            $this->productConcreteTransfer1->getFkProductAbstract(),
            $this->productConcreteTransfer2->getFkProductAbstract(),
        ];

        //Act
        $result = $this->productListRepository->getProductBlacklistsByProductAbstractIds($productAbstractIds);

        //Arrange
        $this->assertCount(2, $result);
        $resultProductAbstractIds = array_map(
            'intval',
            array_column($result, ProductListRepository::COL_ID_PRODUCT_ABSTRACT)
        );
        $this->assertContains($productAbstractIds[0], $resultProductAbstractIds);
        $this->assertContains($productAbstractIds[1], $resultProductAbstractIds);
    }

    /**
     * @return void
     */
    public function testGetProductWhitelistsByProductAbstractIdsReturnsCorrectData(): void
    {
        //Assert
        $this->tester->haveProductList([
            ProductListTransfer::TYPE => SpyProductListTableMap::COL_TYPE_WHITELIST,
            ProductListTransfer::PRODUCT_LIST_PRODUCT_CONCRETE_RELATION => $this->productListProductConcreteRelation->toArray(),
        ]);

        $productAbstractIds = [
            $this->productConcreteTransfer1->getFkProductAbstract(),
            $this->productConcreteTransfer2->getFkProductAbstract(),
        ];

        //Act
        $result = $this->productListRepository->getProductWhiteListsByProductAbstractIds($productAbstractIds);

        //Arrange
        $this->assertCount(2, $result);
        $resultProductAbstractIds = array_map(
            'intval',
            array_column($result, ProductListRepository::COL_ID_PRODUCT_ABSTRACT)
        );
        $this->assertContains($productAbstractIds[0], $resultProductAbstractIds);
        $this->assertContains($productAbstractIds[1], $resultProductAbstractIds);
    }
}
