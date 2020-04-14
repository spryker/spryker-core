<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Communication\Expander;

use Generated\Shared\Transfer\ProductListAggregateFormTransfer;
use Generated\Shared\Transfer\ProductListProductConcreteRelationTransfer;
use Spryker\Zed\ProductListGui\Communication\Form\DataProvider\ProductListCategoryRelationFormDataProvider;

class ProductListAggregateFormDataProviderExpander implements ProductListAggregateFormDataProviderExpanderInterface
{
    /**
     * @var \Spryker\Zed\ProductListGui\Communication\Form\DataProvider\ProductListCategoryRelationFormDataProvider
     */
    protected $productListCategoryRelationFormDataProvider;

    /**
     * @param \Spryker\Zed\ProductListGui\Communication\Form\DataProvider\ProductListCategoryRelationFormDataProvider $productListCategoryRelationFormDataProvider
     */
    public function __construct(ProductListCategoryRelationFormDataProvider $productListCategoryRelationFormDataProvider)
    {
        $this->productListCategoryRelationFormDataProvider = $productListCategoryRelationFormDataProvider;
    }

    /**
     * @param array $options
     *
     * @return array
     */
    public function expandOptions(array $options): array
    {
        return array_merge($options, $this->productListCategoryRelationFormDataProvider->getOptions());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListAggregateFormTransfer $productListAggregateFormTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListAggregateFormTransfer
     */
    public function expandProductListAggregateFormData(ProductListAggregateFormTransfer $productListAggregateFormTransfer): ProductListAggregateFormTransfer
    {
        $productListAggregateFormTransfer->requireProductList();

        $productListTransfer = $productListAggregateFormTransfer->getProductList();

        if (!$productListTransfer->getProductListProductConcreteRelation()) {
            $productListTransfer->setProductListProductConcreteRelation(new ProductListProductConcreteRelationTransfer());
        }

        $productListCategoryRelationTransfer = $this->productListCategoryRelationFormDataProvider
            ->getData($productListTransfer->getIdProductList());

        $assignedProductIds = implode(',', $productListTransfer->getProductListProductConcreteRelation()->getProductIds());

        return $productListAggregateFormTransfer->setAssignedProductIds($assignedProductIds)
            ->setProductListCategoryRelation($productListCategoryRelationTransfer);
    }
}
