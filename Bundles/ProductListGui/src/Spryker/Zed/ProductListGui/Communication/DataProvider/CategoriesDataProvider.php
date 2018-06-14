<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Communication\DataProvider;

use Generated\Shared\Transfer\ProductListTransfer;
use Spryker\Zed\ProductListGui\Communication\Form\CategoriesType;

class CategoriesDataProvider
{
    /**
     * @var array
     */
    protected $idCategoriesWithWrongTemplate = [];

    /**
     */
    public function __construct()
    {
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [
            CategoriesType::OPTION_CATEGORY_ARRAY => $this->getCategoryList(),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListTransfer
     */
    public function getData(ProductListTransfer $productListTransfer)
    {
        $categoryIds = [];

        return $productListTransfer;
    }

    /**
     * @return array
     */
    protected function getCategoryList()
    {
        return [
            1 => 'cat 1',
            2 => 'cat 2',
        ];
    }
}
