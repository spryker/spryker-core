<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryFilter\Business\Model;

use Generated\Shared\Transfer\ProductCategoryFilterTransfer;
use Spryker\Zed\ProductCategoryFilter\Dependency\Service\ProductCategoryFilterToUtilEncodingServiceInterface;
use Spryker\Zed\ProductCategoryFilter\Persistence\ProductCategoryFilterQueryContainerInterface;

class ProductCategoryFilterReader implements ProductCategoryFilterReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductCategoryFilter\Persistence\ProductCategoryFilterQueryContainerInterface
     */
    protected $productCategoryFilterQueryContainer;

    /**
     * @var \Spryker\Zed\ProductCategoryFilter\Dependency\Service\ProductCategoryFilterToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\ProductCategoryFilter\Persistence\ProductCategoryFilterQueryContainerInterface $productCategoryFilterQueryContainer
     * @param \Spryker\Zed\ProductCategoryFilter\Dependency\Service\ProductCategoryFilterToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(ProductCategoryFilterQueryContainerInterface $productCategoryFilterQueryContainer, ProductCategoryFilterToUtilEncodingServiceInterface $utilEncodingService)
    {
        $this->productCategoryFilterQueryContainer = $productCategoryFilterQueryContainer;
        $this->utilEncodingService = $utilEncodingService;
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

        $filterData = $this->utilEncodingService->decodeJson($productCategoryFilterEntity->getFilterData(), true);

        $productCategoryFilterTransfer->setFilterDataArray($filterData);

        return $productCategoryFilterTransfer;
    }

    /**
     * @return array
     */
    public function getAllProductCategoriesIdsWithFilters()
    {
        $categoryIds = [];
        $productCategoryFilters = $this->productCategoryFilterQueryContainer->queryProductCategoryFilter()->find()->toArray();
        foreach ($productCategoryFilters as $productCategoryFilter) {
            $productCategoryFilterTransfer = (new ProductCategoryFilterTransfer())->fromArray($productCategoryFilter);
            $categoryIds[] = $productCategoryFilterTransfer->getFkCategory();
        }

        return $categoryIds;
    }

    /**
     * @param int $categoryId
     *
     * @return \Orm\Zed\ProductCategoryFilter\Persistence\SpyProductCategoryFilter
     */
    protected function getProductCategoryFilterEntityByCategoryId($categoryId)
    {
        return $this->productCategoryFilterQueryContainer
            ->queryProductCategoryFilterByCategoryId($categoryId)
            ->findOne();
    }
}
