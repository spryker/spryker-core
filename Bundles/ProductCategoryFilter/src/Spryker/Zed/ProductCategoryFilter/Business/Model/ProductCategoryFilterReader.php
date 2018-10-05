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
    /**
     * @var \Spryker\Zed\ProductCategoryFilter\Persistence\ProductCategoryFilterQueryContainerInterface
     */
    protected $productCategoryFilterQueryContainer;

    /**
     * @var \Spryker\Zed\ProductCategoryFilter\Business\Model\ProductCategoryFilterTransferGeneratorInterface
     */
    protected $productCategoryFilterTransferGenerator;

    /**
     * @param \Spryker\Zed\ProductCategoryFilter\Persistence\ProductCategoryFilterQueryContainerInterface $productCategoryFilterQueryContainer
     * @param \Spryker\Zed\ProductCategoryFilter\Business\Model\ProductCategoryFilterTransferGenerator $productCategoryFilterTransferGenerator
     */
    public function __construct(ProductCategoryFilterQueryContainerInterface $productCategoryFilterQueryContainer, ProductCategoryFilterTransferGenerator $productCategoryFilterTransferGenerator)
    {
        $this->productCategoryFilterQueryContainer = $productCategoryFilterQueryContainer;
        $this->productCategoryFilterTransferGenerator = $productCategoryFilterTransferGenerator;
    }

    /**
     * @param int $categoryId
     *
     * @return \Generated\Shared\Transfer\ProductCategoryFilterTransfer
     */
    public function findProductCategoryFilterByCategoryId($categoryId)
    {
        $productCategoryFilterEntity = $this->getProductCategoryFilterEntityByCategoryId($categoryId);

        if (!$productCategoryFilterEntity) {
            $productCategoryFilterTransfer = new ProductCategoryFilterTransfer();
            $productCategoryFilterTransfer->setFkCategory($categoryId);
            return $productCategoryFilterTransfer;
        }

        return $this->productCategoryFilterTransferGenerator->generateTransferFromJson(
            $productCategoryFilterEntity->getIdProductCategoryFilter(),
            $categoryId,
            $productCategoryFilterEntity->getFilterData()
        );
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
     * @return \Orm\Zed\ProductCategoryFilter\Persistence\SpyProductCategoryFilter|null
     */
    protected function getProductCategoryFilterEntityByCategoryId($categoryId)
    {
        return $this->productCategoryFilterQueryContainer
            ->queryProductCategoryFilterByCategoryId($categoryId)
            ->findOne();
    }
}
