<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductList\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ProductListBuilder;

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

        // Act
        $productListResponseTransfer = $this->getFacade()->deleteProductList($productListTransfer);

        // Assert
        $this->assertTrue($productListResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testGetProductConcreteIdsByProductListIds()
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
        $this->assertEquals([$productTransfer->getIdProductConcrete()], $productConcreteIds);
    }

    /**
     * @return \Spryker\Zed\Kernel\Business\AbstractFacade|\Spryker\Zed\ProductList\Business\ProductListFacadeInterface
     */
    protected function getFacade()
    {
        return $this->tester->getFacade();
    }
}
