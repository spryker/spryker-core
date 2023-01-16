<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductList\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ProductListBuilder;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ProductListCriteriaTransfer;
use Orm\Zed\ProductList\Persistence\SpyProductListQuery;
use Propel\Runtime\Exception\PropelException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductList
 * @group Business
 * @group Facade
 * @group ProductListFacadeTest
 * Add your own group annotations below this line
 */
class ProductListFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductList\ProductListBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testSaveProductListCreatesProductList(): void
    {
        // Assign
        $productListTransfer = (new ProductListBuilder())->build();

        // Act
        $productListTransfer = $this->getFacade()->saveProductList($productListTransfer);

        //Assert
        $this->assertNotNull($productListTransfer->getIdProductList());
    }

    /**
     * @return void
     */
    public function testSaveProductListCreatesProductListCategoryRelations(): void
    {
        // Assign
        $categoryTransfer = $this->tester->haveCategory();
        /** @var \Generated\Shared\Transfer\ProductListTransfer $productListTransfer */
        $productListTransfer = (new ProductListBuilder())->withProductListCategoryRelation()->build();
        $productListTransfer->getProductListCategoryRelation()->addCategoryIds($categoryTransfer->getIdCategory());

        // Act
        $productListTransfer = $this->getFacade()->saveProductList($productListTransfer);

        // Assert
        $this->assertCount(1, $productListTransfer->getProductListCategoryRelation()->getCategoryIds());
    }

    /**
     * @return void
     */
    public function testSaveProductListCreatesProductListProductConcreteRelations(): void
    {
        // Assign
        $productTransfer = $this->tester->haveProduct();

        /** @var \Generated\Shared\Transfer\ProductListTransfer $productListTransfer */
        $productListTransfer = (new ProductListBuilder())->withProductListProductConcreteRelation()->build();
        $productListTransfer->getProductListProductConcreteRelation()->addProductIds($productTransfer->getIdProductConcrete());

        // Act
        $productListTransfer = $this->getFacade()->saveProductList($productListTransfer);

        // Assert
        $this->assertCount(1, $productListTransfer->getProductListProductConcreteRelation()->getProductIds());
    }

    /**
     * @return void
     */
    public function testSaveProductListUpdatesProductList(): void
    {
        // Assign
        $productListTransfer = $this->tester->haveProductList();
        $productListTransfer->setTitle('TEST');

        // Act
        $savedProductListTransfer = $this->getFacade()->saveProductList($productListTransfer);

        // Assert
        $this->assertSame('TEST', $savedProductListTransfer->getTitle());
    }

    /**
     * @return void
     */
    public function testCreateProductListCreatesProductList(): void
    {
        //Assign
        $productListTransfer = (new ProductListBuilder())->build();

        //Act
        $productListResponseTransfer = $this->getFacade()->createProductList($productListTransfer);

        //Assert
        $this->assertNotNull($productListResponseTransfer->getProductList());
        $this->assertNotNull($productListResponseTransfer->getProductList()->getIdProductList());
    }

    /**
     * @return void
     */
    public function testCreateProductListIsSuccessful(): void
    {
        //Assign
        $productListTransfer = (new ProductListBuilder())->build();

        //Act
        $productListResponseTransfer = $this->getFacade()->createProductList($productListTransfer);

        //Assert
        $this->assertTrue($productListResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testCreateProductListCreatesProductListCategoryRelations(): void
    {
        //Assign
        $categoryTransfer = $this->tester->haveCategory();
        /** @var \Generated\Shared\Transfer\ProductListTransfer $productListTransfer */
        $productListTransfer = (new ProductListBuilder())->withProductListCategoryRelation()->build();
        $productListTransfer->getProductListCategoryRelation()->addCategoryIds($categoryTransfer->getIdCategory());

        //Act
        $productListResponseTransfer = $this->getFacade()->createProductList($productListTransfer);
        $productListTransfer = $productListResponseTransfer->getProductList();

        // Assert
        $this->assertCount(1, $productListTransfer->getProductListCategoryRelation()->getCategoryIds());
    }

    /**
     * @return void
     */
    public function testCreateProductListCreatesProductListProductConcreteRelations(): void
    {
        //Assign
        $productTransfer = $this->tester->haveProduct();

        /** @var \Generated\Shared\Transfer\ProductListTransfer $productListTransfer */
        $productListTransfer = (new ProductListBuilder())->withProductListProductConcreteRelation()->build();
        $productListTransfer->getProductListProductConcreteRelation()->addProductIds($productTransfer->getIdProductConcrete());

        //Act
        $productListResponseTransfer = $this->getFacade()->createProductList($productListTransfer);
        $productListTransfer = $productListResponseTransfer->getProductList();

        //Assert
        $this->assertCount(1, $productListTransfer->getProductListProductConcreteRelation()->getProductIds());
    }

    /**
     * @return void
     */
    public function testUpdateProductListUpdatesProductList(): void
    {
        //Assign
        $productListTransfer = $this->tester->haveProductList();
        $productListTransfer->setTitle('TEST');

        //Act
        $productListResponseTransfer = $this->getFacade()->updateProductList($productListTransfer);

        //Assert
        $this->assertSame('TEST', $productListResponseTransfer->getProductList()->getTitle());
    }

    /**
     * @return void
     */
    public function testDeleteProductListDeletesProductList(): void
    {
        // Assign
        $productListTransfer = $this->tester->haveProductList();

        // Assert
        $this->expectException(PropelException::class);
        $this->expectExceptionMessage('Cannot insert a value for auto-increment primary key (spy_product_list.id_product_list)');

        // Act
        $this->getFacade()->deleteProductList($productListTransfer);
        $this->getFacade()->createProductList($productListTransfer);
    }

    /**
     * @return void
     */
    public function testRemoveProductListDeletesProductList(): void
    {
        // Assign
        $productListTransfer = $this->tester->haveProductList();

        // Act
        $productListResponseTransfer = $this->getFacade()->removeProductList($productListTransfer);
        $productListTransfer = $this->getFacade()->getProductListById($productListTransfer);

        // Assert
        $this->assertTrue($productListResponseTransfer->getIsSuccessful());
        $this->assertNull($productListTransfer->getIdProductList());
    }

    /**
     * @return void
     */
    public function testGetProductConcreteIdsByProductListIds(): void
    {
        // Arrange
        $productTransfer = $this->tester->haveProduct();

        /** @var \Generated\Shared\Transfer\ProductListTransfer $productListTransfer */
        $productListTransfer = (new ProductListBuilder())->withProductListProductConcreteRelation()->build();
        $productListTransfer->getProductListProductConcreteRelation()->addProductIds($productTransfer->getIdProductConcrete());
        $this->getFacade()->createProductList($productListTransfer);

        // Act
        $productConcreteIds = $this->getFacade()->getProductConcreteIdsByProductListIds([$productListTransfer->getIdProductList()]);

        // Assert
        $this->assertIsArray($productConcreteIds);
        // TODO: use assertSame() once the actual return result is of int[], and not string[]
        $this->assertEquals([$productTransfer->getIdProductConcrete()], $productConcreteIds);
    }

    /**
     * @return void
     */
    public function testGetProductListCollectionReturnsCorrectProductListsWithoutPagination(): void
    {
        // Arrange
        $this->tester->ensureDatabaseTableIsEmpty(SpyProductListQuery::create());
        $productListTransfer1 = $this->tester->haveProductList();
        $productListTransfer2 = $this->tester->haveProductList();
        $productListTransfer3 = $this->tester->haveProductList();
        $productListCriteriaTransfer = new ProductListCriteriaTransfer();

        // Act
        $productListCollectionTransfer = $this->getFacade()->getProductListCollection($productListCriteriaTransfer);

        // Assert
        $this->assertCount(3, $productListCollectionTransfer->getProductLists());
        $this->assertSame(
            $productListTransfer1->getIdProductList(),
            $productListCollectionTransfer->getProductLists()->offsetGet(0)->getIdProductList(),
        );
        $this->assertSame(
            $productListTransfer2->getIdProductList(),
            $productListCollectionTransfer->getProductLists()->offsetGet(1)->getIdProductList(),
        );
        $this->assertSame(
            $productListTransfer3->getIdProductList(),
            $productListCollectionTransfer->getProductLists()->offsetGet(2)->getIdProductList(),
        );
    }

    /**
     * @return void
     */
    public function testGetProductListCollectionReturnsPaginatedProductListsWithLimitAndOffset(): void
    {
        // Arrange
        $this->tester->ensureDatabaseTableIsEmpty(SpyProductListQuery::create());
        $this->tester->haveProductList();
        $productListTransfer1 = $this->tester->haveProductList();
        $productListTransfer2 = $this->tester->haveProductList();
        $this->tester->haveProductList();
        $productListCriteriaTransfer = (new ProductListCriteriaTransfer())
            ->setPagination(
                (new PaginationTransfer())->setOffset(1)->setLimit(2),
            );

        // Act
        $productListCollectionTransfer = $this->getFacade()->getProductListCollection($productListCriteriaTransfer);

        // Assert
        $this->assertCount(2, $productListCollectionTransfer->getProductLists());
        $this->assertSame(4, $productListCollectionTransfer->getPagination()->getNbResults());
        $this->assertSame(
            $productListTransfer1->getIdProductList(),
            $productListCollectionTransfer->getProductLists()->offsetGet(0)->getIdProductList(),
        );
        $this->assertSame(
            $productListTransfer2->getIdProductList(),
            $productListCollectionTransfer->getProductLists()->offsetGet(1)->getIdProductList(),
        );
    }

    /**
     * @return void
     */
    public function testGetProductAbstractIdsByProductListIdsShouldNotReturnNullIdsWhenCategoryHasNoProducts(): void
    {
        // Arrange
        $this->tester->ensureDatabaseTableIsEmpty(SpyProductListQuery::create());

        $productListTransfer = $this->tester->haveProductList();
        $categoryTransfer = $this->tester->haveCategory();
        $this->tester->haveProductListCategory($productListTransfer, $categoryTransfer);

        // Act
        $result = $this->getFacade()->getProductAbstractIdsByProductListIds([$productListTransfer->getIdProductList()]);

        // Assert
        $this->assertCount(0, $result);
    }

    /**
     * @return \Spryker\Zed\ProductList\Business\ProductListFacadeInterface
     */
    protected function getFacade()
    {
        return $this->tester->getFacade();
    }
}
