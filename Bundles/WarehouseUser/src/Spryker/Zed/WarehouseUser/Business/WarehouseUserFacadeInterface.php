<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WarehouseUser\Business;

use Generated\Shared\Transfer\WarehouseUserAssignmentCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentCollectionRequestTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentCollectionResponseTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentCollectionTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentCriteriaTransfer;

interface WarehouseUserFacadeInterface
{
    /**
     * Specification:
     * - Fetches collection of WarehouseUserAssignments from the storage.
     * - Uses `WarehouseUserAssignmentCriteriaTransfer.WarehouseUserAssignmentConditions.warehouseUserAssignmentIds` to filter warehouseUserAssignments by `warehouseUserAssignmentIds`.
     * - Uses `WarehouseUserAssignmentCriteriaTransfer.WarehouseUserAssignmentConditions.uuids` to filter warehouseUserAssignments by `uuids`.
     * - Uses `WarehouseUserAssignmentCriteriaTransfer.SortTransfer.field` to set the `order by` field.
     * - Uses `WarehouseUserAssignmentCriteriaTransfer.SortTransfer.isAscending` to set ascending order otherwise will be used descending order.
     * - Uses `WarehouseUserAssignmentCriteriaTransfer.PaginationTransfer.{limit, offset}` to paginate result with limit and offset.
     * - Uses `WarehouseUserAssignmentCriteriaTransfer.PaginationTransfer.{page, maxPerPage}` to paginate result with page and maxPerPage.
     * - Returns `WarehouseUserAssignmentCollectionTransfer` filled with found warehouseUserAssignments.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WarehouseUserAssignmentCriteriaTransfer $warehouseUserAssignmentCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\WarehouseUserAssignmentCollectionTransfer
     */
    public function getWarehouseUserAssignmentCollection(
        WarehouseUserAssignmentCriteriaTransfer $warehouseUserAssignmentCriteriaTransfer
    ): WarehouseUserAssignmentCollectionTransfer;

    /**
     * Specification:
     * - Stores collection of WarehouseUserAssignments to the storage.
     * - Validates `WarehouseUserAssignmentTransfers` before persisting.
     * - Deactivates currently active warehouse user assignments for users if corresponding provided `WarehouseUserAssignmentTransfer` has `isActive` field set to true.
     * - Returns `WarehouseUserAssignmentCollectionResponseTransfer.WarehouseUserTransfer[]` filled with created warehouseUserAssignments.
     * - Returns `WarehouseUserAssignmentCollectionResponseTransfer.ErrorTransfer[]` filled with validation errors.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WarehouseUserAssignmentCollectionRequestTransfer $warehouseUserAssignmentCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\WarehouseUserAssignmentCollectionResponseTransfer
     */
    public function createWarehouseUserAssignmentCollection(
        WarehouseUserAssignmentCollectionRequestTransfer $warehouseUserAssignmentCollectionRequestTransfer
    ): WarehouseUserAssignmentCollectionResponseTransfer;

    /**
     * Specification:
     * - Updates collection of WarehouseUserAssignments in the storage.
     * - Validates `WarehouseUserAssignmentTransfers` before persisting.
     * - Deactivates users' currently active warehouse user assignments if corresponding provided `WarehouseUserAssignmentTransfer` has `isActive` field set to true.
     * - Returns `WarehouseUserAssignmentCollectionResponseTransfer.WarehouseUserAssignmentTransfer[]` filled with updated warehouseUserAssignments.
     * - Returns `WarehouseUserAssignmentCollectionResponseTransfer.ErrorTransfer[]` filled with validation errors.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WarehouseUserAssignmentCollectionRequestTransfer $warehouseUserAssignmentCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\WarehouseUserAssignmentCollectionResponseTransfer
     */
    public function updateWarehouseUserAssignmentCollection(
        WarehouseUserAssignmentCollectionRequestTransfer $warehouseUserAssignmentCollectionRequestTransfer
    ): WarehouseUserAssignmentCollectionResponseTransfer;

    /**
     * Specification:
     * - Deletes collection of WarehouseUserAssignments from the storage by delete criteria.
     * - Uses `WarehouseUserAssignmentCollectionDeleteCriteriaTransfer.warehouseUserAssignmentIds` to filter warehouseUserAssignments by `warehouseUserAssignmentIds`.
     * - Uses `WarehouseUserAssignmentCollectionDeleteCriteriaTransfer.uuids` to filter warehouseUserAssignments by `uuids`.
     * - Uses `WarehouseUserAssignmentCollectionDeleteCriteriaTransfer.isTransactional` to make transactional delete.
     * - Returns `WarehouseUserAssignmentCollectionResponseTransfer.WarehouseUserAssignmentTransfer[]` filled with deleted warehouseUserAssignments.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WarehouseUserAssignmentCollectionDeleteCriteriaTransfer $warehouseUserAssignmentCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\WarehouseUserAssignmentCollectionResponseTransfer
     */
    public function deleteWarehouseUserAssignmentCollection(
        WarehouseUserAssignmentCollectionDeleteCriteriaTransfer $warehouseUserAssignmentCollectionDeleteCriteriaTransfer
    ): WarehouseUserAssignmentCollectionResponseTransfer;
}
