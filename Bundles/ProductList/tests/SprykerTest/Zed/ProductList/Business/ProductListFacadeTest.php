<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductList\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ProductListBuilder;
use Generated\Shared\Transfer\ProductConcretePageSearchTransfer;
use Generated\Shared\Transfer\ProductListMapTransfer;
use Generated\Shared\Transfer\ProductPageSearchTransfer;
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
    protected const TEST_WHITELIST_KEY = 1;
    protected const TEST_BLACKLIST_KEY = 2;
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
     * @return void
     */
    public function testExpandProductConcretePageSearchTransferWithProductLists()
    {
        // Arrange
        $productConcretePageSearchTransfer = new ProductConcretePageSearchTransfer();
        $productConcrete = $this->tester->haveProduct();
        $productConcretePageSearchTransfer->setFkProduct($productConcrete->getIdProductConcrete());

        // Act
        $this->getFacade()->expandProductConcretePageSearchTransferWithProductLists(
            $productConcretePageSearchTransfer
        );

        // Assert
        $this->assertInstanceOf(ProductListMapTransfer::class, $productConcretePageSearchTransfer->getProductListMap());
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
        $this->getFacade()->saveProductList($productListTransfer);

        // Act
        $productConcreteIds = $this->getFacade()->getProductConcreteIdsByProductListIds([$productListTransfer->getIdProductList()]);

        // Assert
        $this->assertIsArray($productConcreteIds);
        $this->assertEquals([$productTransfer->getIdProductConcrete()], $productConcreteIds);
    }

    /**
     * @return void
     */
    public function testMapProductDataToProductListMapTransfer()
    {
        // Arrange
        $productData = [
            ProductPageSearchTransfer::PRODUCT_LIST_MAP => [
                ProductListMapTransfer::WHITELISTS => [self::TEST_WHITELIST_KEY],
                ProductListMapTransfer::BLACKLISTS => [self::TEST_BLACKLIST_KEY],
            ],
        ];
        $productListMapTransfer = new ProductListMapTransfer();

        // Act
        $this->getFacade()->mapProductDataToProductListMapTransfer($productData, $productListMapTransfer);

        // Assert
        $this->assertIsArray($productListMapTransfer->getWhitelists());
        $this->assertIsArray($productListMapTransfer->getBlacklists());
        $this->assertEquals([self::TEST_WHITELIST_KEY], $productListMapTransfer->getWhitelists());
        $this->assertEquals([self::TEST_BLACKLIST_KEY], $productListMapTransfer->getBlacklists());
    }

    /**
     * @return \Spryker\Zed\Kernel\Business\AbstractFacade|\Spryker\Zed\ProductList\Business\ProductListFacadeInterface
     */
    protected function getFacade()
    {
        return $this->tester->getFacade();
    }
}
