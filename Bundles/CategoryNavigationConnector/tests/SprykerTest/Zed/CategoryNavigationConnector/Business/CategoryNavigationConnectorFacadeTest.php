<?php

namespace SprykerTest\Zed\CategoryNavigationConnector\Business;

use Codeception\Test\Unit;

class CategoryNavigationConnectorFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\CategoryNavigationConnector\BusinessTester
     */
    protected $tester;

    public function testUpdateNavigationNodeIsActiveToCategoryNodeIsActive()
    {
        // Arrange
        $category = $this->tester->haveCategory();

        // Act
        $this->tester->getFacade()->updateCategoryNavigationNodesIsActive($category);

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
