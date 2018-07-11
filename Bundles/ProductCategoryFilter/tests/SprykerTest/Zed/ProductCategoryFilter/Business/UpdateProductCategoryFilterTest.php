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
 * @group UpdateProductCategoryFilterTest
 * Add your own group annotations below this line
 *
 * @property \SprykerTest\Zed\ProductCategoryFilter\ProductCategoryFilterBusinessTester $tester
 */
class UpdateProductCategoryFilterTest extends Unit
{
    /**
     * @return void
     */
    public function testUpdateProductCategoryFilterChangesDataInDatabase()
    {
        // Arrange
        $newFilterData = [
            ProductCategoryFilterTransfer::FILTERS => [
                [
                    ProductCategoryFilterItemTransfer::KEY => 'newKey1',
                    ProductCategoryFilterItemTransfer::LABEL => 'newLabel1',
                    ProductCategoryFilterItemTransfer::IS_ACTIVE => true,
                ],
                [
                    ProductCategoryFilterItemTransfer::KEY => 'newKey2',
                    ProductCategoryFilterItemTransfer::LABEL => 'newLabel2',
                    ProductCategoryFilterItemTransfer::IS_ACTIVE => false,
                ],
            ],
        ];

        $productCategoryFilter = $this->tester->haveProductCategoryFilter();

        // Act
        $productCategoryFilter->fromArray($newFilterData);
        $newProductCategoryFilter = $this->tester->getFacade()->updateProductCategoryFilter($productCategoryFilter);

        $productCategoryFilterFromDb = $this->tester->getFacade()->findProductCategoryFilterByCategoryId($productCategoryFilter->getFkCategory());
        // Assert
        $this->assertEquals($newProductCategoryFilter->getFilters(), $productCategoryFilterFromDb->getFilters(), 'Product category filter should contain new data');
        $this->tester->assertTouchActive(
            ProductCategoryFilterConfig::RESOURCE_TYPE_PRODUCT_CATEGORY_FILTER,
            $productCategoryFilter->getFkCategory(),
            'Product category filter should have been touched as active.'
        );
    }
}
