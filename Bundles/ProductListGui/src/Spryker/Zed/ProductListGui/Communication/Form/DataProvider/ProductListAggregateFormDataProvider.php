<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\ProductListAggregateFormTransfer;

class ProductListAggregateFormDataProvider
{
    /**
     * @var \Spryker\Zed\ProductListGui\Communication\Form\DataProvider\ProductListFormDataProvider
     */
    protected $productListFormDataProvider;

    /**
     * @var \Spryker\Zed\ProductListGui\Communication\Form\DataProvider\ProductListCategoryRelationFormDataProvider
     */
    protected $productListCategoryRelationFormDataProvider;

    /**
     * @param \Spryker\Zed\ProductListGui\Communication\Form\DataProvider\ProductListFormDataProvider $productListFormDataProvider
     * @param \Spryker\Zed\ProductListGui\Communication\Form\DataProvider\ProductListCategoryRelationFormDataProvider $productListCategoryRelationFormDataProvider
     */
    public function __construct(
        ProductListFormDataProvider $productListFormDataProvider,
        ProductListCategoryRelationFormDataProvider $productListCategoryRelationFormDataProvider
    ) {
        $this->productListFormDataProvider = $productListFormDataProvider;
        $this->productListCategoryRelationFormDataProvider = $productListCategoryRelationFormDataProvider;
    }

    /**
     * @param int|null $idProductList
     *
     * @return \Generated\Shared\Transfer\ProductListAggregateFormTransfer
     */
    public function getData(?int $idProductList = null): ProductListAggregateFormTransfer
    {
        $assignedProductIds = [];
        $productListTransfer = $this->productListFormDataProvider->getData($idProductList);
        $productListCategoryRelation = $this->productListCategoryRelationFormDataProvider->getData($productListTransfer->getIdProductList());

        $productListProductConcreteRelationTransfer = $productListTransfer->getProductListProductConcreteRelation();
        if ($productListProductConcreteRelationTransfer && $productListProductConcreteRelationTransfer->getProductIds()) {
            foreach ($productListTransfer->getProductListProductConcreteRelation()->getProductIds() as $productId) {
                $assignedProductIds[] = $productId;
            }
        }

        $aggregateFormTransfer = new ProductListAggregateFormTransfer();
        $aggregateFormTransfer->setProductList($productListTransfer);
        $aggregateFormTransfer->setProductListCategoryRelation($productListCategoryRelation);
        $aggregateFormTransfer = $this->setAssignedProducts($aggregateFormTransfer, $assignedProductIds);

        return $aggregateFormTransfer;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->productListCategoryRelationFormDataProvider->getOptions();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListAggregateFormTransfer $aggregateFormTransfer
     * @param int[] $assignedProductIds
     *
     * @return \Generated\Shared\Transfer\ProductListAggregateFormTransfer
     */
    protected function setAssignedProducts(
        ProductListAggregateFormTransfer $aggregateFormTransfer,
        array $assignedProductIds
    ): ProductListAggregateFormTransfer {
        $aggregateFormTransfer->setAssignedProductIds(implode(',', $assignedProductIds));

        return $aggregateFormTransfer;
    }
}
