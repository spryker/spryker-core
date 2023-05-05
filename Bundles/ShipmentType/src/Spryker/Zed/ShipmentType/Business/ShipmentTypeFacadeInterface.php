<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentType\Business;

use Generated\Shared\Transfer\ShipmentTypeCollectionRequestTransfer;
use Generated\Shared\Transfer\ShipmentTypeCollectionResponseTransfer;
use Generated\Shared\Transfer\ShipmentTypeCollectionTransfer;
use Generated\Shared\Transfer\ShipmentTypeCriteriaTransfer;

interface ShipmentTypeFacadeInterface
{
    /**
     * Specification:
     * - Retrieves shipment type entities filtered by criteria from Persistence.
     * - Uses `ShipmentTypeCriteriaTransfer.ShipmentTypeConditions.shipmentTypeIds` to filter by shipment type IDs.
     * - Uses `ShipmentTypeCriteriaTransfer.ShipmentTypeConditions.uuids` to filter by shipment type UUIDs.
     * - Uses `ShipmentTypeCriteriaTransfer.ShipmentTypeConditions.keys` to filter by shipment type keys.
     * - Uses `ShipmentTypeCriteriaTransfer.ShipmentTypeConditions.isActive` to filter by shipment type active status.
     * - Uses `ShipmentTypeCriteriaTransfer.ShipmentTypeConditions.storeNames` to filter shipmentTypes by related store names.
     * - Uses `ShipmentTypeCriteriaTransfer.ShipmentTypeConditions.withStores` to load store relations.
     * - Uses `ShipmentTypeCriteriaTransfer.sort.field` to set the 'order by' field.
     * - Uses `ShipmentTypeCriteriaTransfer.sort.isAscending` to set ascending/descending order.
     * - Uses `ShipmentTypeCriteriaTransfer.pagination.{limit, offset}` to paginate results with limit and offset.
     * - Uses `ShipmentTypeCriteriaTransfer.pagination.{page, maxPerPage}` to paginate results with page and maxPerPage.
     * - Returns `ShipmentTypeCollectionTransfer` filled with found shipment types.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShipmentTypeCriteriaTransfer $shipmentTypeCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeCollectionTransfer
     */
    public function getShipmentTypeCollection(
        ShipmentTypeCriteriaTransfer $shipmentTypeCriteriaTransfer
    ): ShipmentTypeCollectionTransfer;

    /**
     * Specification:
     * - Requires `ShipmentTypeCollectionRequestTransfer.isTransactional` to be set.
     * - Requires `ShipmentTypeCollectionRequestTransfer.shipmentTypes` to be set.
     * - Requires `ShipmentTypeTransfer.key` to be set.
     * - Requires `ShipmentTypeTransfer.name` to be set.
     * - Requires `ShipmentTypeTransfer.isActive` to be set.
     * - Requires `ShipmentTypeTransfer.storeRelation` to be set.
     * - Requires `ShipmentTypeTransfer.storeRelation.stores` to be set.
     * - Requires at least one `ShipmentTypeTransfer.storeRelation.stores.name` to be set.
     * - Validates shipment type name length.
     * - Validates shipment type key length.
     * - Validates shipment type key uniqueness in scope of request collection.
     * - Validates shipment type key uniqueness among already persisted shipment types.
     * - Validates store existence using `StoreTransfer.name`.
     * - Adds validation errors to `ShipmentTypeCollectionResponseTransfer.errors` if any occurs.
     * - Uses `ShipmentTypeCollectionRequestTransfer.isTransactional` for transactional operation.
     * - Stores shipment types at Persistence.
     * - Stores shipment type store relations at Persistence.
     * - Returns `ShipmentTypeCollectionResponseTransfer` filled with persisted and invalid (if any) shipment types.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShipmentTypeCollectionRequestTransfer $shipmentTypeCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeCollectionResponseTransfer
     */
    public function createShipmentTypeCollection(
        ShipmentTypeCollectionRequestTransfer $shipmentTypeCollectionRequestTransfer
    ): ShipmentTypeCollectionResponseTransfer;

    /**
     * Specification:
     * - Requires `ShipmentTypeCollectionRequestTransfer.isTransactional` to be set.
     * - Requires `ShipmentTypeCollectionRequestTransfer.shipmentTypes` to be set.
     * - Requires `ShipmentTypeTransfer.uuid` to be set.
     * - Requires `ShipmentTypeTransfer.key` to be set.
     * - Requires `ShipmentTypeTransfer.name` to be set.
     * - Requires `ShipmentTypeTransfer.isActive` to be set.
     * - Requires `ShipmentTypeTransfer.storeRelation` to be set.
     * - Requires `ShipmentTypeTransfer.storeRelation.stores` to be set.
     * - Requires at least one `ShipmentTypeTransfer.storeRelation.stores.name` to be set.
     * - Validates shipment type existence using `ShipmentTypeTransfer.uuid`.
     * - Validates shipment type name length.
     * - Validates shipment type key length.
     * - Validates shipment type key uniqueness in scope of request collection.
     * - Validates shipment type key uniqueness among already persisted shipment types.
     * - Validates store existence using `StoreTransfer.name`.
     * - Adds validation errors to `ShipmentTypeCollectionResponseTransfer.errors` if any occurs.
     * - Uses `ShipmentTypeCollectionRequestTransfer.isTransactional` for transactional operation.
     * - Stores updated shipment types at Persistence.
     * - Stores updated shipment type store relations at Persistence.
     * - Returns `ShipmentTypeCollectionResponseTransfer` filled with persisted and invalid (if any) shipment types.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShipmentTypeCollectionRequestTransfer $shipmentTypeCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeCollectionResponseTransfer
     */
    public function updateShipmentTypeCollection(
        ShipmentTypeCollectionRequestTransfer $shipmentTypeCollectionRequestTransfer
    ): ShipmentTypeCollectionResponseTransfer;
}
