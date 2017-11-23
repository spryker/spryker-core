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
        $filterData = 'testFilterData';
        $productCategory = $this->tester->haveCategory();
        $productCategoryFilter = (new ProductCategoryFilterTransfer())->fromArray([
            ProductCategoryFilterTransfer::ID_PRODUCT_CATEGORY_FILTER => null,
            ProductCategoryFilterTransfer::FK_CATEGORY => $productCategory->getIdCategory(),
            ProductCategoryFilterTransfer::FILTER_DATA => $filterData,
        ]);

        // Act
        $productCategoryFilter = $this->tester->getFacade()->createProductCategoryFilter($productCategoryFilter);

        // Assert
        $this->assertGreaterThan(0, $productCategoryFilter->getIdProductCategoryFilter(), 'Product category filter should have ID after creation.');
        $this->assertSame($filterData, $productCategoryFilter->getFilterData(), 'Product category filter contain correct data');
        $this->assertSame($productCategory->getIdCategory(), $productCategoryFilter->getFkCategory());

        $this->tester->assertTouchActive(
            ProductCategoryFilterConfig::RESOURCE_TYPE_PRODUCT_CATEGORY_FILTER,
            $productCategoryFilter->getFkCategory(),
            'Product category filter should have been touched as active.'
        );
    }
}
