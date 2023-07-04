<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentType\Business;

use Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferShipmentTypeCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferShipmentTypeIteratorCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;

interface ProductOfferShipmentTypeFacadeInterface
{
    /**
     * Specification:
     * - Requires `ProductOfferTransfer.idProductOffer` to be set.
     * - Expands `ProductOfferTransfer` with related shipment types.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    public function expandProductOfferWithShipmentTypes(ProductOfferTransfer $productOfferTransfer): ProductOfferTransfer;

    /**
     * Specification:
     * - Requires `ProductOfferTransfer.idProductOffer` to be set.
     * - Requires `ShipmentTypeTransfer.idShipmentType` to be set for each `ShipmentTypeTransfer` in `ProductOfferTransfer.shipmentTypes` collection.
     * - Iterates over `ProductOfferTransfer.shipmentTypes`.
     * - Persists product offer shipment types to persistence.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    public function createProductOfferShipmentTypes(ProductOfferTransfer $productOfferTransfer): ProductOfferTransfer;

    /**
     * Specification:
     * - Requires `ProductOfferTransfer.idProductOffer` to be set.
     * - Requires `ShipmentTypeTransfer.idShipmentType` to be set for each `ShipmentTypeTransfer` in `ProductOfferTransfer.shipmentTypes` collection.
     * - Deletes redundant product offer shipment types from Persistence.
     * - Persists missed product offer shipment types to Persistence.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    public function updateProductOfferShipmentTypes(ProductOfferTransfer $productOfferTransfer): ProductOfferTransfer;

    /**
     * Specification:
     * - Retrieves product offer shipment type entities filtered by criteria from Persistence.
     * - Does not expand `ProductOfferShipmentTypeTransfers` with product offer and shipment type data.
     * - Uses `ProductOfferShipmentTypeCriteriaTransfer.ProductOfferShipmentTypeConditions.productOfferShipmentTypeIds` to filter by product offer shipment type IDs.
     * - Uses `ProductOfferShipmentTypeCriteriaTransfer.ProductOfferShipmentTypeConditions.productOfferIds` to filter by product offer IDs.
     * - Uses `ProductOfferShipmentTypeCriteriaTransfer.ProductOfferShipmentTypeConditions.shipmentTypeIds` to filter by shipment type IDs.
     * - Uses `ProductOfferShipmentTypeCriteriaTransfer.ProductOfferShipmentTypeConditions.groupByIdProductOffer` to group shipment type IDs by product offer ID.
     * - Uses `ProductOfferShipmentTypeCriteriaTransfer.sort.field` to set the 'order by' field.
     * - Uses `ProductOfferShipmentTypeCriteriaTransfer.sort.isAscending` to set ascending/descending order.
     * - Uses `ProductOfferShipmentTypeCriteriaTransfer.pagination.{limit, offset}` to paginate results with limit and offset.
     * - Uses `ProductOfferShipmentTypeCriteriaTransfer.pagination.{page, maxPerPage}` to paginate results with page and maxPerPage.
     * - Returns `ProductOfferShipmentTypeCollectionTransfer` filled with found product offer shipment types.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferShipmentTypeCriteriaTransfer $productOfferShipmentTypeCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionTransfer
     */
    public function getProductOfferShipmentTypeCollection(
        ProductOfferShipmentTypeCriteriaTransfer $productOfferShipmentTypeCriteriaTransfer
    ): ProductOfferShipmentTypeCollectionTransfer;

    /**
     * Specification:
     * - Requires `ProductOfferShipmentTypeIteratorCriteriaTransfer.productOfferShipmentTypeIteratorConditions` to be set.
     * - Iterates over product offer shipment type entities retrieved from Persistence according to criteria filters.
     * - Uses `ProductOfferShipmentTypeIteratorCriteriaTransfer.productOfferShipmentTypeIteratorConditions.productOfferIds` to filter by product offer IDs.
     * - Uses `ProductOfferShipmentTypeIteratorCriteriaTransfer.productOfferShipmentTypeIteratorConditions.productOfferApprovalStatuses` to filter by product offer approval statuses.
     * - Uses `ProductOfferShipmentTypeIteratorCriteriaTransfer.productOfferShipmentTypeIteratorConditions.isActiveProductOffer` to filter by product offer active status.
     * - Uses `ProductOfferShipmentTypeIteratorCriteriaTransfer.productOfferShipmentTypeIteratorConditions.isActiveShipmentType` to filter by shipment type active status.
     * - Uses `ProductOfferShipmentTypeIteratorCriteriaTransfer.productOfferShipmentTypeIteratorConditions.isActiveProductOfferConcreteProduct` to filter by product offers product concrete active status.
     * - Groups shipment type IDs by product offer IDs.
     * - Expands `ProductOfferShipmentTypeTransfers` with product offer data.
     * - Expands `ProductOfferShipmentTypeTransfers` with shipment type data.
     * - Returns `ProductOfferShipmentTypeTransfers` filled with found product offer shipment types data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferShipmentTypeIteratorCriteriaTransfer $productOfferShipmentTypeIteratorCriteriaTransfer
     *
     * @return iterable<\ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferShipmentTypeTransfer>>
     */
    public function getProductOfferShipmentTypesIterator(
        ProductOfferShipmentTypeIteratorCriteriaTransfer $productOfferShipmentTypeIteratorCriteriaTransfer
    ): iterable;
}
