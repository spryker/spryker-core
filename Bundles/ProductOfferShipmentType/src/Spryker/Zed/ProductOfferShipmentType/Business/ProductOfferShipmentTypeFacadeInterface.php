<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentType\Business;

use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionRequestTransfer;
use Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionResponseTransfer;
use Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferShipmentTypeCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferShipmentTypeIteratorCriteriaTransfer;

interface ProductOfferShipmentTypeFacadeInterface
{
    /**
     * Specification:
     * - Requires `ProductOfferCollectionTransfer.productOffer.idProductOffer` to be set.
     * - Expects `ProductOfferCollectionTransfer.productOffers` to be provided.
     * - Expands `ProductOfferTransfer.shipmentTypes` with related shipment types from persistence.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferCollectionTransfer $productOfferCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferCollectionTransfer
     */
    public function expandProductOfferCollectionWithShipmentTypes(
        ProductOfferCollectionTransfer $productOfferCollectionTransfer
    ): ProductOfferCollectionTransfer;

    /**
     * Specification:
     * - Requires `ProductOfferShipmentTypeCollectionRequestTransfer.productOffers` to be set.
     * - Requires `ProductOfferShipmentTypeCollectionRequestTransfer.isTransactional` to be set.
     * - Requires `ProductOfferTransfer.productOfferReference` to be set.
     * - Requires `ProductOfferTransfer.shipmentTypes.uuid` to be set.
     * - Validates product offer reference existence using `ProductOfferTransfer.productOfferReference`.
     * - Validates product offer reference uniqueness in scope of request collection.
     * - Validates shipment type existence using `ProductOfferTransfer.shipmentTypes.uuid`.
     * - Validates shipment type uniqueness for each `ProductOfferShipmentTypeCollectionRequestTransfer.productOffers`.
     * - Uses `ProductOfferShipmentTypeCollectionRequestTransfer.isTransactional` for transactional operation.
     * - Throws {@link \Spryker\Zed\ProductOfferShipmentType\Business\Exception\ProductOfferValidationException} when `ProductOfferShipmentTypeCollectionRequestTransfer.throwExceptionOnValidation` enabled and validation fails.
     * - Stores valid product offer shipment type entities to persistence.
     * - Returns `ProductOfferShipmentTypeCollectionResponseTransfer` with product offers and errors if any occurred.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionRequestTransfer $productOfferShipmentTypeCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionResponseTransfer
     */
    public function saveProductOfferShipmentTypes(
        ProductOfferShipmentTypeCollectionRequestTransfer $productOfferShipmentTypeCollectionRequestTransfer
    ): ProductOfferShipmentTypeCollectionResponseTransfer;

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
