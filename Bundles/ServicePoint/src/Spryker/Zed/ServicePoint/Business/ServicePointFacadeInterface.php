<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePoint\Business;

use Generated\Shared\Transfer\ServicePointCollectionRequestTransfer;
use Generated\Shared\Transfer\ServicePointCollectionResponseTransfer;
use Generated\Shared\Transfer\ServicePointCollectionTransfer;
use Generated\Shared\Transfer\ServicePointCriteriaTransfer;

interface ServicePointFacadeInterface
{
    /**
     * Specification:
     * - Retrieves service point entities filtered by criteria from Persistence.
     * - Uses `ServicePointCriteriaTransfer.servicePointConditions.uuids` to filter by service point uuids.
     * - Uses `ServicePointCriteriaTransfer.servicePointConditions.keys` to filter by service point keys.
     * - Uses `ServicePointCriteriaTransfer.servicePointConditions.withStoreRelations` to load store relations.
     * - Inverses uuids filtering in case `ServicePointCriteriaTransfer.servicePointConditions.isUuidsConditionInversed` is set to `true`.
     * - Uses `ServicePointCriteriaTransfer.sort.field` to set the 'order by' field.
     * - Uses `ServicePointCriteriaTransfer.sort.isAscending` to set ascending/descending order.
     * - Uses `ServicePointCriteriaTransfer.pagination.{limit, offset}` to paginate results with limit and offset.
     * - Uses `ServicePointCriteriaTransfer.pagination.{page, maxPerPage}` to paginate results with page and maxPerPage.
     * - Returns `ServicePointCollectionTransfer` filled with found service points.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ServicePointCriteriaTransfer $servicePointCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ServicePointCollectionTransfer
     */
    public function getServicePointCollection(
        ServicePointCriteriaTransfer $servicePointCriteriaTransfer
    ): ServicePointCollectionTransfer;

    /**
     * Specification:
     * - Requires `ServicePointCollectionRequestTransfer.isTransactional` to be set.
     * - Requires `ServicePointCollectionRequestTransfer.servicePoints` to be set.
     * - Requires `ServicePointTransfer.key` to be set.
     * - Requires `ServicePointTransfer.name` to be set.
     * - Requires `ServicePointTransfer.isActive` to be set.
     * - Requires at least one `ServicePointTransfer.storeRelation.stores.name` to be set.
     * - Validates service point name length.
     * - Validates service point key length.
     * - Validates service point key uniqueness in scope of request collection.
     * - Validates service point key uniqueness among already persisted service points.
     * - Validates store existence using `StoreTransfer.name`.
     * - Uses `ServicePointCollectionRequestTransfer.isTransactional` for transactional operation.
     * - Stores service points at Persistence.
     * - Stores service point store relations at Persistence.
     * - Returns `ServicePointCollectionRequestTransfer` with persisted service points and errors if any occurred.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ServicePointCollectionRequestTransfer $servicePointCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ServicePointCollectionResponseTransfer
     */
    public function createServicePointCollection(
        ServicePointCollectionRequestTransfer $servicePointCollectionRequestTransfer
    ): ServicePointCollectionResponseTransfer;

    /**
     * Specification:
     * - Requires `ServicePointCollectionRequestTransfer.isTransactional` to be set.
     * - Requires `ServicePointCollectionRequestTransfer.servicePoints` to be set.
     * - Requires `ServicePointTransfer.uuid` to be set.
     * - Requires `ServicePointTransfer.key` to be set.
     * - Requires `ServicePointTransfer.name` to be set.
     * - Requires `ServicePointTransfer.isActive` to be set.
     * - Requires at least one `ServicePointTransfer.storeRelation.stores.name` to be set.
     * - Validates service point existence using `ServicePointTransfer.uuid`.
     * - Validates service point name length.
     * - Validates service point key length.
     * - Validates service point key uniqueness in scope of request collection.
     * - Validates service point key uniqueness among already persisted service points.
     * - Validates store existence using `StoreTransfer.name`.
     * - Uses `ServicePointCollectionRequestTransfer.isTransactional` for transactional operation.
     * - Stores updated service points at Persistence.
     * - Stores updated service point store relations at Persistence.
     * - Returns `ServicePointCollectionRequestTransfer` with persisted service points and errors if any occurred.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ServicePointCollectionRequestTransfer $servicePointCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ServicePointCollectionResponseTransfer
     */
    public function updateServicePointCollection(
        ServicePointCollectionRequestTransfer $servicePointCollectionRequestTransfer
    ): ServicePointCollectionResponseTransfer;
}
