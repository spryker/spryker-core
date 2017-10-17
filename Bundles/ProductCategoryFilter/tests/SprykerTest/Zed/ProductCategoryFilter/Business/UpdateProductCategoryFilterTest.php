<?php

namespace SprykerTest\Zed\ProductCategoryFilter\Business;

use Codeception\Test\Unit;
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
        $oldFilterData = 'old test filter data';
        $newFilterData = 'new test filter data';
        $productCategoryFilter = $this->tester->haveProductCategoryFilter([ProductCategoryFilterTransfer::FILTER_DATA => $oldFilterData]);

        // Act
        $productCategoryFilter->setFilterData($newFilterData);
        $productCategoryFilter = $this->tester->getFacade()->updateProductCategoryFilter($productCategoryFilter);

        $productCategoryFilterFromDb = $this->tester->getFacade()->findProductCategoryFilterByCategoryId($productCategoryFilter->getFkCategory());
        // Assert
        $this->assertSame($newFilterData, $productCategoryFilterFromDb->getFilterData(), 'Product category filter should contain new data');
        $this->tester->assertTouchActive(
            ProductCategoryFilterConfig::RESOURCE_TYPE_PRODUCT_CATEGORY_FILTER,
            $productCategoryFilter->getIdProductCategoryFilter(),
            'Product category filter should have been touched as active.'
        );
    }
}
