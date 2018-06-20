<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductList\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ProductListBuilder;
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
    public function testDeleteProductListDeletesProductList(): void
    {
        // Assign
        $productListTransfer = $this->tester->haveProductList();

        // Assert
        $this->expectException(PropelException::class);
        $this->expectExceptionMessage('Cannot insert a value for auto-increment primary key (spy_product_list.id_product_list)');

        // Act
        $this->getFacade()->deleteProductList($productListTransfer);
        $this->getFacade()->saveProductList($productListTransfer);
    }

    /**
     * @return \Spryker\Zed\Kernel\Business\AbstractFacade|\Spryker\Zed\ProductList\Business\ProductListFacadeInterface
     */
    protected function getFacade()
    {
        return $this->tester->getFacade();
    }
}
