<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryFilter\Business\Model;

use Generated\Shared\Transfer\ProductCategoryFilterTransfer;
use Spryker\Zed\ProductCategoryFilter\Persistence\ProductCategoryFilterQueryContainerInterface;

class ProductCategoryFilterReader implements ProductCategoryFilterReaderInterface
{
    use RetrievesProductCategoryFilterEntityTrait;

    /**
     * @param \Spryker\Zed\ProductCategoryFilter\Persistence\ProductCategoryFilterQueryContainerInterface $productCategoryFilterQueryContainer
     */
    public function __construct(ProductCategoryFilterQueryContainerInterface $productCategoryFilterQueryContainer)
    {
        $this->productCategoryFilterQueryContainer = $productCategoryFilterQueryContainer;
    }

    /**
     * @param int $categoryId
     *
     * @return \Generated\Shared\Transfer\ProductCategoryFilterTransfer
     */
    public function findProductCategoryFilterByCategoryId($categoryId)
    {
        $productCategoryFilterEntity = $this->getProductCategoryFilterEntityByCategoryId($categoryId);

        $productCategoryFilterTransfer = new ProductCategoryFilterTransfer();
        $productCategoryFilterTransfer->setFkCategory($categoryId);

        if (!$productCategoryFilterEntity) {
            return $productCategoryFilterTransfer;
        }

        $productCategoryFilterTransfer = $productCategoryFilterTransfer->fromArray($productCategoryFilterEntity->toArray(), true);

        $filterData = json_decode($productCategoryFilterEntity->getFilterData(), true);

        $productCategoryFilterTransfer->setFilterDataArray($filterData);

        return $productCategoryFilterTransfer;
    }

    /**
     * @return array
     */
    public function getAllProductCategoriesWithFilters()
    {
        $categoryIds = [];
        $productCategoryFilters = $this->productCategoryFilterQueryContainer->queryProductCategoryFilter()->find()->toArray();
        foreach ($productCategoryFilters as $productCategoryFilter) {
            $productCategoryFilterTransfer = (new ProductCategoryFilterTransfer())->fromArray($productCategoryFilter);
            $categoryIds[] = $productCategoryFilterTransfer->getFkCategory();
        }

        return $categoryIds;
    }
}
