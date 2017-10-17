<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductCategoryFilter\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\ProductCategoryFilterBuilder;
use Generated\Shared\Transfer\ProductCategoryFilterTransfer;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;
use SprykerTest\Zed\Category\Helper\CategoryDataHelper;

class ProductCategoryFilterHelper extends Module
{
    use LocatorHelperTrait;

    /**
     * @param array $override
     *
     * @return ProductCategoryFilterTransfer
     */
    public function haveProductCategoryFilter($override = [])
    {
        $categoryDataHelper = $this->getCategoryDataHelper();
        $category = $categoryDataHelper->haveCategory();
        $defaults = [
            ProductCategoryFilterTransfer::ID_PRODUCT_CATEGORY_FILTER => null,
            ProductCategoryFilterTransfer::FK_CATEGORY => $category->getIdCategory(),
            ProductCategoryFilterTransfer::FILTER_DATA => 'filterData',
        ];

        $productCategoryFilter = (new ProductCategoryFilterBuilder(array_merge($defaults, $override)))->build();

        $productCategoryFacade = $this->getLocator()->productCategoryFilter()->facade();
        return $productCategoryFacade->createProductCategoryFilter($productCategoryFilter);
    }

    /**
     *
     * @return CategoryDataHelper|Module
     */
    protected function getCategoryDataHelper()
    {
        return $this->getModule('\\' . CategoryDataHelper::class);
    }
}
