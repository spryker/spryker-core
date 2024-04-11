<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategory\Persistence;

use Generated\Shared\Transfer\CategoryCollectionTransfer;
use Generated\Shared\Transfer\ProductCategoryCollectionTransfer;
use Generated\Shared\Transfer\ProductCategoryCriteriaTransfer;

interface ProductCategoryRepositoryInterface
{
    /**
     * @param int $idProductAbstract
     * @param int $idLocale
     *
     * @return \Generated\Shared\Transfer\CategoryCollectionTransfer
     */
    public function getCategoryTransferCollectionByIdProductAbstract(int $idProductAbstract, int $idLocale): CategoryCollectionTransfer;

    /**
     * @param array<int> $categoryIds
     *
     * @return array<int>
     */
    public function getProductConcreteIdsByCategoryIds(array $categoryIds): array;

    /**
     * @param \Generated\Shared\Transfer\ProductCategoryCriteriaTransfer $productCategoryCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductCategoryCollectionTransfer
     */
    public function getProductCategoryCollection(ProductCategoryCriteriaTransfer $productCategoryCriteriaTransfer): ProductCategoryCollectionTransfer;

    /**
     * @param array<int> $categoryNodeIds
     *
     * @return \Generated\Shared\Transfer\ProductCategoryCollectionTransfer
     */
    public function findProductCategoryChildrenMappingsByCategoryNodeIds(array $categoryNodeIds): ProductCategoryCollectionTransfer;

    /**
     * @param array<int> $categoryIds
     *
     * @return \Generated\Shared\Transfer\ProductCategoryCollectionTransfer
     */
    public function findProductCategoryByCategoryIds(array $categoryIds): ProductCategoryCollectionTransfer;
}
