<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductCategoryFilter\Business;

use Codeception\Test\Unit;
use Spryker\Shared\ProductCategoryFilter\ProductCategoryFilterConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductCategoryFilter
 * @group Business
 * @group DeleteProductCategoryFilterTest
 * Add your own group annotations below this line
 *
 * @property \SprykerTest\Zed\ProductCategoryFilter\ProductCategoryFilterBusinessTester $tester
 */
class DeleteProductCategoryFilterTest extends Unit
{
    /**
     * @return void
     */
    public function testDeleteProductCategoryFiltersRemovesFromDatabase()
    {
        // Arrange
        $productCategoryFilter = $this->tester->haveProductCategoryFilter();

        // Act
        $this->tester->getFacade()->deleteProductCategoryFilterByCategoryId($productCategoryFilter->getFkCategory());

        // Assert
        $this->assertNull($this->tester->getFacade()->findProductCategoryFilterByCategoryId($productCategoryFilter->getFkCategory())->getIdProductCategoryFilter());
        $this->tester->assertTouchDeleted(
            ProductCategoryFilterConfig::RESOURCE_TYPE_PRODUCT_CATEGORY_FILTER,
            $productCategoryFilter->getFkCategory(),
            'Product category filter should have been touched as deleted.'
        );
    }
}
