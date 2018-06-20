<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Communication\DataProvider;

use Generated\Shared\Transfer\ProductListTransfer;
use Spryker\Zed\ProductListGui\Business\ProductListGuiFacadeInterface;
use Spryker\Zed\ProductListGui\Communication\Form\ProductListCategoryRelationType;

class CategoriesDataProvider
{
    /**
     * @var \Spryker\Zed\ProductListGui\Business\ProductListGuiFacadeInterface
     */
    protected $facade;

    /**
     * @param \Spryker\Zed\ProductListGui\Business\ProductListGuiFacadeInterface $facade
     */
    public function __construct(
        ProductListGuiFacadeInterface $facade
    ) {
        $this->facade = $facade;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [
            ProductListCategoryRelationType::OPTION_CATEGORY_ARRAY => $this->getCategoryList(),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListTransfer
     */
    public function getData(ProductListTransfer $productListTransfer)
    {
        return $productListTransfer;
    }

    /**
     * @return int[] [<category key> => <category id>] where <category key>:="<category id> - <category name>"
     */
    protected function getCategoryList(): array
    {
        $uniqueCategoryNames = [];
        $categoryNames = $this->facade->getAllCategoryNames();
        foreach ($categoryNames as $idCategory => $categoryName) {
            $categoryKey = sprintf('%s - %s', $idCategory, $categoryName);
            $uniqueCategoryNames[$categoryKey] = $idCategory;
        }

        return $uniqueCategoryNames;
    }
}
