<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePoint\Business;

use Generated\Shared\Transfer\ServicePointAddressCollectionRequestTransfer;
use Generated\Shared\Transfer\ServicePointAddressCollectionResponseTransfer;
use Generated\Shared\Transfer\ServicePointAddressCollectionTransfer;
use Generated\Shared\Transfer\ServicePointAddressCriteriaTransfer;
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
     * - Uses `ServicePointCriteriaTransfer.servicePointConditions.servicePointIds` to filter by service point ids.
     * - Uses `ServicePointCriteriaTransfer.servicePointConditions.withStoreRelations` to load store relations.
     * - Uses `ServicePointCriteriaTransfer.servicePointConditions.withAddressRelation` to load address relation.
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

    /**
     * Specification:
     * - Retrieves service point address entities filtered by criteria from Persistence.
     * - Uses `ServicePointAddressCriteriaTransfer.servicePointAddressConditions.servicePointUuids` to filter by service point uuids.
     * - Uses `ServicePointAddressCriteriaTransfer.servicePointAddressConditions.uuids` to filter by service point address uuids.
     * - Uses `ServicePointAddressCriteriaTransfer.sort.field` to set the 'order by' field.
     * - Uses `ServicePointAddressCriteriaTransfer.sort.isAscending` to set ascending/descending order.
     * - Uses `ServicePointAddressCriteriaTransfer.pagination.{limit, offset}` to paginate results with limit and offset.
     * - Uses `ServicePointAddressCriteriaTransfer.pagination.{page, maxPerPage}` to paginate results with page and maxPerPage.
     * - Returns `ServicePointAddressCollectionTransfer` filled with found service point addresses.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ServicePointAddressCriteriaTransfer $servicePointAddressCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ServicePointAddressCollectionTransfer
     */
    public function getServicePointAddressCollection(
        ServicePointAddressCriteriaTransfer $servicePointAddressCriteriaTransfer
    ): ServicePointAddressCollectionTransfer;

    /**
     * Specification:
     * - Requires `ServicePointAddressCollectionRequestTransfer.isTransactional` to be set.
     * - Requires `ServicePointAddressCollectionRequestTransfer.servicePointAddresses` to be set.
     * - Requires `ServicePointAddressTransfer.servicePoint.uuid` to be set.
     * - Requires `ServicePointAddressTransfer.country.iso2Code` to be set.
     * - Requires `ServicePointAddressTransfer.address1` to be set.
     * - Requires `ServicePointAddressTransfer.address2` to be set.
     * - Requires `ServicePointAddressTransfer.zipCode` to be set.
     * - Requires `ServicePointAddressTransfer.city` to be set.
     * - Validates service point existence using `ServicePointAddressTransfer.servicePoint.uuid`.
     * - Validates region existence using `ServicePointAddressTransfer.region.uuid`.
     * - Validates country existence using `ServicePointAddressTransfer.country.iso2Code`.
     * - Validates region matches country using `RegionTransfer.uuid` and `ServicePointAddressTransfer.country.iso2Code`.
     * - Validates service point address address1 length.
     * - Validates service point address address2 length.
     * - Validates service point address address3 length.
     * - Validates service point address zip code length.
     * - Validates service point address city length.
     * - Validates service point has single address using `ServicePointAddressTransfer.servicePoint.uuid`.
     * - Uses `ServicePointAddressCollectionRequestTransfer.isTransactional` for transactional operation.
     * - Stores service point addresses at Persistence.
     * - Returns `ServicePointAddressCollectionRequestTransfer` with persisted service point addresses and errors if any occurred.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ServicePointAddressCollectionRequestTransfer $servicePointAddressCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ServicePointAddressCollectionResponseTransfer
     */
    public function createServicePointAddressCollection(
        ServicePointAddressCollectionRequestTransfer $servicePointAddressCollectionRequestTransfer
    ): ServicePointAddressCollectionResponseTransfer;

    /**
     * Specification:
     * - Requires `ServicePointAddressCollectionRequestTransfer.isTransactional` to be set.
     * - Requires `ServicePointAddressCollectionRequestTransfer.servicePointAddresses` to be set.
     * - Requires `ServicePointAddressTransfer.servicePoint.uuid` to be set.
     * - Requires `ServicePointAddressTransfer.country.iso2Code` to be set.
     * - Requires `ServicePointAddressTransfer.uuid` to be set.
     * - Requires `ServicePointAddressTransfer.address1` to be set.
     * - Requires `ServicePointAddressTransfer.address2` to be set.
     * - Requires `ServicePointAddressTransfer.zipCode` to be set.
     * - Requires `ServicePointAddressTransfer.city` to be set.
     * - Validates service point address existence using `ServicePointAddressTransfer.uuid`.
     * - Validates service point existence using `ServicePointAddressTransfer.servicePoint.uuid`.
     * - Validates region existence using `ServicePointAddressTransfer.region.uuid`.
     * - Validates country existence using `ServicePointAddressTransfer.country.iso2Code`.
     * - Validates region matches country using `RegionTransfer.uuid` and `ServicePointAddressTransfer.country.iso2Code`.
     * - Validates service point address address1 length.
     * - Validates service point address address2 length.
     * - Validates service point address address3 length.
     * - Validates service point address zip code length.
     * - Validates service point address city length.
     * - Validates service point has single address using `ServicePointAddressTransfer.servicePoint.uuid`.
     * - Uses `ServicePointAddressCollectionRequestTransfer.isTransactional` for transactional operation.
     * - Updates service point addresses at Persistence.
     * - Returns `ServicePointAddressCollectionRequestTransfer` with persisted service point addresses and errors if any occurred.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ServicePointAddressCollectionRequestTransfer $servicePointAddressCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ServicePointAddressCollectionResponseTransfer
     */
    public function updateServicePointAddressCollection(
        ServicePointAddressCollectionRequestTransfer $servicePointAddressCollectionRequestTransfer
    ): ServicePointAddressCollectionResponseTransfer;
}
