<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelation\Business;

use Generated\Shared\Transfer\ProductRelationTransfer;

/**
 * @method \Spryker\Zed\ProductRelation\Business\ProductRelationBusinessFactory getFactory()
 */
interface ProductRelationFacadeInterface
{
    /**
     * Specification:
     *  - Create product relation type is not persisted
     *  - Create Product relation
     *  - Save related product based on given query
     *  - Touch product relation collector
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $productRelationTransfer
     *
     * @return int
     */
    public function createProductRelation(ProductRelationTransfer $productRelationTransfer);

    /**
     * Specification:
     *  - Create product relation type is not persisted
     *  - Update Product relation
     *  - Save related product based on given query, remove old relations
     *  - Touch product relation collector
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $productRelationTransfer
     *
     * @throws \Exception
     * @throws \Throwable
     * @throws \Spryker\Zed\ProductRelation\Business\Exception\ProductRelationNotFoundException
     *
     * @return void
     */
    public function updateProductRelation(ProductRelationTransfer $productRelationTransfer);

    /**
     * Specification:
     *  - Read product relations from persistence
     *
     * @api
     *
     * @param int $idProductRelation
     *
     * @return \Generated\Shared\Transfer\ProductRelationTransfer|null
     */
    public function findProductRelationById($idProductRelation);

    /**
     * Specification:
     *  - Returns product relation type list as stored in persistence
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\ProductRelationTypeTransfer[]
     */
    public function getProductRelationTypeList();

    /**
     * Specification:
     * - Activates product relation and touches relation collector
     *
     * @api
     *
     * @param int $idProductRelation
     *
     * @throws \Spryker\Zed\ProductRelation\Business\Exception\ProductRelationNotFoundException
     *
     * @return void
     */
    public function activateProductRelation($idProductRelation);

    /**
     * Specification:
     * - Deactivates product relation and touches relation collector
     *
     * @api
     *
     * @param int $idProductRelation
     *
     * @throws \Spryker\Zed\ProductRelation\Business\Exception\ProductRelationNotFoundException
     *
     * @return void
     */
    public function deactivateProductRelation($idProductRelation);

    /**
     * Specification:
     * - Rebuilds all active relations with selected is_rebuild_scheduled = true, reruns stored query.
     *
     * @api
     *
     * @return void
     */
    public function rebuildRelations();

    /**
     * Specification:
     * - Deletes product relation from persistence
     * - Touches delete relation
     *
     * @api
     *
     * @param int $idProductRelation
     *
     * @return bool
     */
    public function deleteProductRelation($idProductRelation);
}
