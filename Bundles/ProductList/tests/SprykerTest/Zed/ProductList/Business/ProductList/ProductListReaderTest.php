<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductList\Business\ProductList;

use Codeception\Test\Unit;
use Spryker\Zed\ProductList\Business\ProductList\ProductListReader;
use Spryker\Zed\ProductList\Business\ProductList\ProductListReaderInterface;
use Spryker\Zed\ProductList\Business\ProductListCategoryRelation\ProductListCategoryRelationReaderInterface;
use Spryker\Zed\ProductList\Business\ProductListProductConcreteRelation\ProductListProductConcreteRelationReaderInterface;
use Spryker\Zed\ProductList\Dependency\Facade\ProductListToProductFacadeInterface;
use Spryker\Zed\ProductList\Persistence\ProductListRepositoryInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductList
 * @group Business
 * @group ProductList
 * @group ProductListReaderTest
 * Add your own group annotations below this line
 */
class ProductListReaderTest extends Unit
{
    /**
     * @dataProvider productListIdsDataProvider
     *
     * @param array<int> $productListIdsByProduct
     * @param array<int> $productListIdsByCategory
     *
     * @return void
     */
    public function testGetProductWhitelistIdsByIdProductReturnsIndexedArray(
        array $productListIdsByProduct,
        array $productListIdsByCategory
    ): void {
        // Arrange
        $productListReader = $this->createProductListReader($productListIdsByProduct, $productListIdsByCategory);

        // Act
        $productListIds = $productListReader->getProductWhitelistIdsByIdProduct(1);

        // Assert
        $this->assertTrue(
            array_values($productListIds) === $productListIds,
        );
    }

    /**
     * @dataProvider productListIdsDataProvider
     *
     * @param array<int> $productListIdsByProduct
     * @param array<int> $productListIdsByCategory
     *
     * @return void
     */
    public function testGetProductBlacklistIdsByIdProductReturnsIndexedArray(
        array $productListIdsByProduct,
        array $productListIdsByCategory
    ): void {
        // Arrange
        $productListReader = $this->createProductListReader($productListIdsByProduct, $productListIdsByCategory);

        // Act
        $productListIds = $productListReader->getProductBlacklistIdsByIdProduct(1);

        // Assert
        $this->assertTrue(
            array_values($productListIds) === $productListIds,
        );
    }

    /**
     * @return array<array>
     */
    protected function productListIdsDataProvider(): array
    {
        return [
            [
                [1, 2, 3], [4, 5, 6],
            ],

            [
                [1, 2, 3], [2, 5, 6],
            ],
        ];
    }

    /**
     * @param array<int> $productListIdsByProduct
     * @param array<int> $productListIdsByCategory
     *
     * @return \Spryker\Zed\ProductList\Business\ProductList\ProductListReaderInterface
     */
    protected function createProductListReader(
        array $productListIdsByProduct,
        array $productListIdsByCategory
    ): ProductListReaderInterface {
        $productListRepositoryMock = $this->getMockBuilder(ProductListRepositoryInterface::class)->getMock();
        $productListRepositoryMock->method('getProductConcreteProductListIdsForType')->willReturn($productListIdsByProduct);
        $productListRepositoryMock->method('getProductConcreteProductListIdsRelatedToCategoriesForType')->willReturn($productListIdsByCategory);

        $productListCategoryRelationReaderMock = $this->getMockBuilder(ProductListCategoryRelationReaderInterface::class)->getMock();
        $productListProductConcreteRelationReaderMock = $this->getMockBuilder(ProductListProductConcreteRelationReaderInterface::class)->getMock();
        $productListToProductFacade = $this->getMockBuilder(ProductListToProductFacadeInterface::class)->getMock();

        return new ProductListReader(
            $productListRepositoryMock,
            $productListCategoryRelationReaderMock,
            $productListProductConcreteRelationReaderMock,
            $productListToProductFacade,
        );
    }
}
