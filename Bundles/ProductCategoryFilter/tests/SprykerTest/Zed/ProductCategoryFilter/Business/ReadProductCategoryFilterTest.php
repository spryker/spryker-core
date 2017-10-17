<?php

namespace SprykerTest\Zed\ProductCategoryFilter\Business;

use Codeception\Test\Unit;

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
        $this->assertSame($productCategoryFilter->getFilterData(), $productCategoryFilterFromDb->getFilterData(), 'Product category filter contain correct data');
        $this->assertSame($productCategoryFilter->getFkCategory(), $productCategoryFilterFromDb->getFkCategory(), 'Product category filter related to correct category');
    }
}
