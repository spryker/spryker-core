<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagementStoreConnector\Business;

use Generated\Shared\Transfer\StoreRelationTransfer;

interface ProductManagementStoreConnectorFacadeInterface
{
    /**
     * Specification:
     * - Updates the store relations of the provided product abstract.
     * - Removes store relations from persistent storage if they are not set in the provided store list.
     * - Adds/keeps store relations to persistent storage if they are set in the provided store list.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $storeRelation
     *
     * @return void
     */
    public function saveProductAbstractStoreRelation(StoreRelationTransfer $storeRelation);

    /**
     * Specification:
     * - Populates the product abstract store relations in the provided store relation transfer.
     * - Uses the provided entity id to identify the product abstract entity.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $storeRelationTransfer
     *
     * @return \Generated\Shared\Transfer\StoreRelationTransfer
     */
    public function getProductAbstractStoreRelation(StoreRelationTransfer $storeRelationTransfer);
}
