<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Communication\DataProvider;

use Generated\Shared\Transfer\ProductListTransfer;
use Spryker\Zed\ProductListGui\Communication\Form\ProductListForm;

class ProductListDataProvider
{
    /**
     * @var \Spryker\Zed\ProductListGui\Communication\DataProvider\CategoriesDataProvider
     */
    protected $categoriesDataProvider;

    /**
     * @var \Spryker\Zed\ProductListGui\Communication\DataProvider\ProductListProductConcreteRelationDataProvider
     */
    protected $productConcreteRelationDataProvider;

    /**
     * @param \Spryker\Zed\ProductListGui\Communication\DataProvider\CategoriesDataProvider $categoriesDataProvider
     * @param \Spryker\Zed\ProductListGui\Communication\DataProvider\ProductListProductConcreteRelationDataProvider $productConcreteRelationDataProvider
     */
    public function __construct(
        CategoriesDataProvider $categoriesDataProvider,
        ProductListProductConcreteRelationDataProvider $productConcreteRelationDataProvider
    ) {
        $this->categoriesDataProvider = $categoriesDataProvider;
        $this->productConcreteRelationDataProvider = $productConcreteRelationDataProvider;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [
            ProductListForm::FIELD_CATEGORIES => $this->categoriesDataProvider->getOptions(),
            ProductListForm::FIELD_PRODUCTS => $this->productConcreteRelationDataProvider->getOptions(),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListTransfer
     */
    public function getData(ProductListTransfer $productListTransfer)
    {
    }
}
