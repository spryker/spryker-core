<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelation\Persistence;

use Generated\Shared\Transfer\ProductRelationCriteriaFilterTransfer;
use Generated\Shared\Transfer\ProductRelationCriteriaTransfer;
use Generated\Shared\Transfer\ProductRelationTransfer;
use Generated\Shared\Transfer\ProductSelectorTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;

/**
 * @method \Spryker\Zed\ProductRelation\Persistence\ProductRelationPersistenceFactory getFactory()
 */
interface ProductRelationRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductRelationCriteriaTransfer $productRelationCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductRelationTransfer|null
     */
    public function findUniqueProductRelation(
        ProductRelationCriteriaTransfer $productRelationCriteriaTransfer
    ): ?ProductRelationTransfer;

    /**
     * @return \Generated\Shared\Transfer\ProductAttributeKeyTransfer[]
     */
    public function findProductAttributes(): array;

    /**
     * @param int $idProductAbstract
     * @param int $idLocale
     *
     * @return \Generated\Shared\Transfer\ProductSelectorTransfer
     */
    public function findProductWithCategoriesByFkLocale(int $idProductAbstract, int $idLocale): ProductSelectorTransfer;

    /**
     * @param int $idProductRelation
     *
     * @return \Generated\Shared\Transfer\ProductRelationTransfer|null
     */
    public function findProductRelationById(int $idProductRelation): ?ProductRelationTransfer;

    /**
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $productRelationTransfer
     *
     * @return int
     */
    public function getRelatedProductsCount(ProductRelationTransfer $productRelationTransfer): int;

    /**
     * @param \Generated\Shared\Transfer\ProductRelationCriteriaFilterTransfer $productRelationCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer[]
     */
    public function findRelatedProductsByCriteriaFilter(ProductRelationCriteriaFilterTransfer $productRelationCriteriaFilterTransfer): array;

    /**
     * @param int $idProductRelation
     *
     * @return \Generated\Shared\Transfer\StoreRelationTransfer
     */
    public function getStoreRelationByIdProductRelation(int $idProductRelation): StoreRelationTransfer;
}
