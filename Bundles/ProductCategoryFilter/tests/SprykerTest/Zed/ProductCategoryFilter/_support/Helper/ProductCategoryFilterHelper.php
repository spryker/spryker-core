<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductCategoryFilter\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\ProductCategoryFilterBuilder;
use Generated\Shared\Transfer\ProductCategoryFilterItemTransfer;
use Generated\Shared\Transfer\ProductCategoryFilterTransfer;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;
use SprykerTest\Zed\Category\Helper\CategoryDataHelper;

class ProductCategoryFilterHelper extends Module
{
    use LocatorHelperTrait;

    /**
     * @param array $override
     *
     * @return \Generated\Shared\Transfer\ProductCategoryFilterTransfer
     */
    public function haveProductCategoryFilter(array $override = [])
    {
        $categoryDataHelper = $this->getCategoryDataHelper();
        $category = $categoryDataHelper->haveCategory();
        $filterData = [
            ProductCategoryFilterTransfer::ID_PRODUCT_CATEGORY_FILTER => null,
            ProductCategoryFilterTransfer::FK_CATEGORY => $category->getIdCategory(),
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

        $productCategoryFilter = (new ProductCategoryFilterBuilder(array_merge($filterData, $override)))->build();

        $productCategoryFacade = $this->getLocator()->productCategoryFilter()->facade();

        return $productCategoryFacade->createProductCategoryFilter($productCategoryFilter);
    }

    /**
     * @return \SprykerTest\Zed\Category\Helper\CategoryDataHelper|\Codeception\Module
     */
    protected function getCategoryDataHelper()
    {
        return $this->getModule('\\' . CategoryDataHelper::class);
    }
}
