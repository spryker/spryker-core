<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypeServicePoint\Business;

use Generated\Shared\Transfer\ShipmentTypeServiceTypeCollectionTransfer;
use Generated\Shared\Transfer\ShipmentTypeServiceTypeCriteriaTransfer;

interface ShipmentTypeServicePointFacadeInterface
{
    /**
     * Specification:
     * - Retrieves shipment type service type entities filtered by criteria from Persistence.
     * - Uses `ShipmentTypeServiceTypeCriteriaTransfer.shipmentTypeServiceTypeConditions.shipmentTypeIds` to filter by shipment type IDs.
     * - Uses `ShipmentTypeServiceTypeCriteriaTransfer.shipmentTypeServiceTypeConditions.withServiceTypeRelations` to expand `ShipmentTypeServiceTypeTransfers` with service type data.
     * - Uses `ShipmentTypeServiceTypeCriteriaTransfer.sort.field` to set the 'order by' field.
     * - Uses `ShipmentTypeServiceTypeCriteriaTransfer.sort.isAscending` to set ascending/descending order.
     * - Uses `ShipmentTypeServiceTypeCriteriaTransfer.pagination.{limit, offset}` to paginate results with limit and offset.
     * - Uses `ShipmentTypeServiceTypeCriteriaTransfer.pagination.{page, maxPerPage}` to paginate results with page and maxPerPage.
     * - Returns `ShipmentTypeServiceTypeCollectionTransfer` filled with found shipment type service types.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShipmentTypeServiceTypeCriteriaTransfer $shipmentTypeServiceTypeCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeServiceTypeCollectionTransfer
     */
    public function getShipmentTypeServiceTypeCollection(
        ShipmentTypeServiceTypeCriteriaTransfer $shipmentTypeServiceTypeCriteriaTransfer
    ): ShipmentTypeServiceTypeCollectionTransfer;
}
