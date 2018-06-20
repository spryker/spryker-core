<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductCategoryFilter\Business;

use Codeception\Test\Unit;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ProductCategoryFilter
 * @group Business
 * @group ReadProductCategoryFilterTest
 * Add your own group annotations below this line
 *
 * @property \SprykerTest\Zed\ProductCategoryFilter\ProductCategoryFilterBusinessTester $tester
 */
class ReadProductCategoryFilterTest extends Unit
{
    /**
     * @return void
     */
    public function testReadProductCategoryFiltersFetchesFromDatabase()
    {
        // Arrange
        $productCategoryFilter = $this->tester->haveProductCategoryFilter();

        // Act
        $productCategoryFilterFromDb = $this->tester->getFacade()->findProductCategoryFilterByCategoryId($productCategoryFilter->getFkCategory());

        // Assert
        $this->assertEquals($productCategoryFilter->getFilters(), $productCategoryFilterFromDb->getFilters(), 'Product category filter contain correct data');
        $this->assertSame($productCategoryFilter->getFkCategory(), $productCategoryFilterFromDb->getFkCategory(), 'Product category filter related to correct category');
    }

    /**
     * @return void
     */
    public function testGetAllProductCategoriesWithFiltersFromDatabase()
    {
        // Arrange
        $productCategoryFilter1 = $this->tester->haveProductCategoryFilter();
        $productCategoryFilter2 = $this->tester->haveProductCategoryFilter();

        // Act
        $categories = $this->tester->getFacade()->getAllProductCategoriesWithFilters();

        // Assert
        $this->assertSame([$productCategoryFilter1->getFkCategory(), $productCategoryFilter2->getFkCategory()], $categories);
    }
}
