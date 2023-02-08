<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WarehouseUsersBackendApi\Dependency\Facade;

use Generated\Shared\Transfer\WarehouseUserAssignmentCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentCollectionRequestTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentCollectionResponseTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentCollectionTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentCriteriaTransfer;

interface WarehouseUsersBackendApiToWarehouseUserFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\WarehouseUserAssignmentCriteriaTransfer $warehouseUserAssignmentCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\WarehouseUserAssignmentCollectionTransfer
     */
    public function getWarehouseUserAssignmentCollection(
        WarehouseUserAssignmentCriteriaTransfer $warehouseUserAssignmentCriteriaTransfer
    ): WarehouseUserAssignmentCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\WarehouseUserAssignmentCollectionRequestTransfer $warehouseUserAssignmentCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\WarehouseUserAssignmentCollectionResponseTransfer
     */
    public function createWarehouseUserAssignmentCollection(
        WarehouseUserAssignmentCollectionRequestTransfer $warehouseUserAssignmentCollectionRequestTransfer
    ): WarehouseUserAssignmentCollectionResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\WarehouseUserAssignmentCollectionRequestTransfer $warehouseUserAssignmentCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\WarehouseUserAssignmentCollectionResponseTransfer
     */
    public function updateWarehouseUserAssignmentCollection(
        WarehouseUserAssignmentCollectionRequestTransfer $warehouseUserAssignmentCollectionRequestTransfer
    ): WarehouseUserAssignmentCollectionResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\WarehouseUserAssignmentCollectionDeleteCriteriaTransfer $warehouseUserAssignmentCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\WarehouseUserAssignmentCollectionResponseTransfer
     */
    public function deleteWarehouseUserAssignmentCollection(
        WarehouseUserAssignmentCollectionDeleteCriteriaTransfer $warehouseUserAssignmentCollectionDeleteCriteriaTransfer
    ): WarehouseUserAssignmentCollectionResponseTransfer;
}
