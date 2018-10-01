<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductCategoryFilter\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductCategoryFilterItemTransfer;
use Generated\Shared\Transfer\ProductCategoryFilterTransfer;
use Spryker\Shared\ProductCategoryFilter\ProductCategoryFilterConfig;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ProductCategoryFilter
 * @group Business
 * @group CreateProductCategoryFilterTest
 * Add your own group annotations below this line
 *
 * @property \SprykerTest\Zed\ProductCategoryFilter\ProductCategoryFilterBusinessTester $tester
 */
class CreateProductCategoryFilterTest extends Unit
{
    /**
     * @return void
     */
    public function testCreateProductCategoryFilterPersistNewEntitiesToDatabase()
    {
        // Arrange
        $productCategory = $this->tester->haveCategory();

        $filterData = [
            ProductCategoryFilterTransfer::ID_PRODUCT_CATEGORY_FILTER => null,
            ProductCategoryFilterTransfer::FK_CATEGORY => $productCategory->getIdCategory(),
            ProductCategoryFilterTransfer::FILTERS => [
                [
                    ProductCategoryFilterItemTransfer::KEY => 'key1',
                    ProductCategoryFilterItemTransfer::LABEL => 'label1',
                    ProductCategoryFilterItemTransfer::IS_ACTIVE => true,
                ],
                [
                    ProductCategoryFilterItemTransfer::KEY => 'key2',
                    ProductCategoryFilterItemTransfer::LABEL => 'label2',
                    ProductCategoryFilterItemTransfer::IS_ACTIVE => false,
                ],
            ],
        ];

        $testProductCategoryFilterTransfer = (new ProductCategoryFilterTransfer())->fromArray($filterData);

        // Act
        $productCategoryFilterTransfer = $this->tester->getFacade()->createProductCategoryFilter($testProductCategoryFilterTransfer);

        // Assert
        $this->assertGreaterThan(0, $productCategoryFilterTransfer->getIdProductCategoryFilter(), 'Product category filter should have ID after creation.');
        $this->assertSame($productCategoryFilterTransfer->getFilters(), $testProductCategoryFilterTransfer->getFilters(), 'Product category filter contain correct data');
        $this->assertSame($productCategory->getIdCategory(), $productCategoryFilterTransfer->getFkCategory());

        $this->tester->assertTouchActive(
            ProductCategoryFilterConfig::RESOURCE_TYPE_PRODUCT_CATEGORY_FILTER,
            $productCategoryFilterTransfer->getFkCategory(),
            'Product category filter should have been touched as active.'
        );
    }
}
