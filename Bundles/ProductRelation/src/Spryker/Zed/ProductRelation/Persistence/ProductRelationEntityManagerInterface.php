<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelation\Persistence;

use Generated\Shared\Transfer\ProductRelationTransfer;
use Generated\Shared\Transfer\ProductRelationTypeTransfer;

/**
 * @method \Spryker\Zed\ProductRelation\Persistence\ProductRelationPersistenceFactory getFactory()
 */
interface ProductRelationEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $productRelationTransfer
     *
     * @return \Generated\Shared\Transfer\ProductRelationTransfer
     */
    public function createProductRelation(ProductRelationTransfer $productRelationTransfer): ProductRelationTransfer;

    /**
     * @param int[] $abstractProductIds
     * @param int $idProductRelation
     *
     * @return void
     */
    public function saveRelatedProducts(array $abstractProductIds, int $idProductRelation): void;

    /**
     * @param int[] $idStores
     * @param int $idProductRelation
     *
     * @return void
     */
    public function addProductRelationStoreRelationsForStores(
        array $idStores,
        int $idProductRelation
    ): void;

    /**
     * @param int[] $idStores
     * @param int $idProductRelation
     *
     * @return void
     */
    public function removeProductRelationStoreRelationsForStores(
        array $idStores,
        int $idProductRelation
    ): void;

    /**
     * @param \Generated\Shared\Transfer\ProductRelationTypeTransfer $productRelationTypeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductRelationTypeTransfer
     */
    public function saveProductRelationType(ProductRelationTypeTransfer $productRelationTypeTransfer): ProductRelationTypeTransfer;

    /**
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $productRelationTransfer
     *
     * @return \Generated\Shared\Transfer\ProductRelationTransfer|null
     */
    public function updateProductRelation(ProductRelationTransfer $productRelationTransfer): ?ProductRelationTransfer;

    /**
     * @param int $idProductRelation
     *
     * @return void
     */
    public function removeRelatedProductsByIdProductRelation(int $idProductRelation): void;

    /**
     * @param int $idProductRelation
     *
     * @return void
     */
    public function deleteProductRelationById(int $idProductRelation): void;

    /**
     * @param int $idProductRelation
     *
     * @return void
     */
    public function deleteProductRelationStoresByIdProductRelation(int $idProductRelation): void;
}
