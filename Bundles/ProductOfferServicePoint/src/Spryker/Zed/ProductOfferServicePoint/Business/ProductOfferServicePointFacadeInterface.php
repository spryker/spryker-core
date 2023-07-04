<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePoint\Business;

use Generated\Shared\Transfer\IterableProductOfferServicesCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferServiceCollectionRequestTransfer;
use Generated\Shared\Transfer\ProductOfferServiceCollectionResponseTransfer;
use Generated\Shared\Transfer\ProductOfferServiceCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferServiceCriteriaTransfer;

interface ProductOfferServicePointFacadeInterface
{
    /**
     * Specification:
     * - Requires `ProductOfferCollectionTransfer.productOffers` to be set.
     * - Requires `ProductOfferTransfer.idProductOffer` to be set.
     * - Expands `ProductOfferTransfer.services` with services from persistence.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferCollectionTransfer $productOfferCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferCollectionTransfer
     */
    public function expandProductOfferCollectionWithServices(ProductOfferCollectionTransfer $productOfferCollectionTransfer): ProductOfferCollectionTransfer;

    /**
     * Specification:
     * - Requires `ProductOfferServiceCollectionRequestTransfer.productOffers` to be set.
     * - Requires `ProductOfferServiceCollectionRequestTransfer.isTransactional` to be set.
     * - Requires `ProductOfferTransfer.productOfferReference` to be set.
     * - Requires `ProductOfferTransfer.services.uuid` to be set.
     * - Validates product offer reference existence using `ProductOfferTransfer.productOfferReference`.
     * - Validates service existence using `ProductOfferTransfer.services.uuid`.
     * - Validates service uniqueness in scope of request collection.
     * - Validates product offer has single service point.
     * - Uses `ProductOfferServiceCollectionRequestTransfer.isTransactional` for transactional operation.
     * - Throws {@link \Spryker\Zed\ProductOfferServicePoint\Business\Exception\ProductOfferValidationException} when `ProductOfferServiceCollectionRequestTransfer.throwExceptionOnValidation` enabled and validation fails.
     * - Stores updated product offer service entities to persistence.
     * - Returns `ProductOfferServiceCollectionResponseTransfer` with product offers and errors if any occurred.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferServiceCollectionRequestTransfer $productOfferServiceCollectionRequestTransfer
     *
     * @throws \Spryker\Zed\ProductOfferServicePoint\Business\Exception\ProductOfferValidationException
     *
     * @return \Generated\Shared\Transfer\ProductOfferServiceCollectionResponseTransfer
     */
    public function saveProductOfferServices(
        ProductOfferServiceCollectionRequestTransfer $productOfferServiceCollectionRequestTransfer
    ): ProductOfferServiceCollectionResponseTransfer;

    /**
     * Specification:
     * - Retrieves product offer service entities from Persistence.
     * - Uses `ProductOfferServiceCriteriaTransfer.productOfferServiceConditions.productOfferServiceIds` to filter by product offer service IDs.
     * - Uses `ProductOfferServiceCriteriaTransfer.productOfferServiceConditions.productOfferIds` to filter by product offer IDs.
     * - Uses `ProductOfferServiceCriteriaTransfer.productOfferServiceConditions.serviceIds` to filter by service IDs.
     * - Uses `ProductOfferServiceCriteriaTransfer.productOfferServiceConditions.groupByIdProductOffer` to group by product offer IDs.
     * - Uses `ProductOfferServiceCriteriaTransfer.sort.field` to set the 'order by' field.
     * - Uses `ProductOfferServiceCriteriaTransfer.sort.isAscending` to set ascending/descending order.
     * - Uses `ProductOfferServiceCriteriaTransfer.pagination.{limit, offset}` to paginate results with limit and offset.
     * - Uses `ProductOfferServiceCriteriaTransfer.pagination.{page, maxPerPage}` to paginate results with page and maxPerPage.
     * - If grouping by product offer IDs is not requested, `ProductOfferServicesCollectionTransfer.productOfferServices` will represent individual product offer service entities.
     * - Otherwise, `ProductOfferServicesCollectionTransfer.productOfferServices` will contain all services related to the same product offer ID, and `ProductOfferServicesCollectionTransfer.productOfferServices.idProductOfferService` will not be set.
     * - Returns `ProductOfferServiceCollectionTransfer` filled with found product offer services.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferServiceCriteriaTransfer $productOfferServiceCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferServiceCollectionTransfer
     */
    public function getProductOfferServiceCollection(
        ProductOfferServiceCriteriaTransfer $productOfferServiceCriteriaTransfer
    ): ProductOfferServiceCollectionTransfer;

    /**
     * Specification:
     * - Requires `IterableProductOfferServicesCriteriaTransfer.iterableProductOfferServicesConditions` to be set.
     * - Expects `IterableProductOfferServicesCriteriaTransfer.iterableProductOfferServicesConditions.productOfferIds` to be set.
     * - Retrieves product offer service entities filtered by product offer IDs and grouped by product offer ID from Persistence.
     * - Uses `IterableProductOfferServicesCriteriaTransfer.iterableProductOfferServicesConditions.productOfferIds` to filter by product offer IDs.
     * - Uses `IterableProductOfferServicesCriteriaTransfer.iterableProductOfferServicesConditions.productOfferApprovalStatuses` to filter by product offer approval statuses.
     * - Uses `IterableProductOfferServicesCriteriaTransfer.iterableProductOfferServicesConditions.isActiveProductOffer` to filter by active product offers.
     * - Uses `IterableProductOfferServicesCriteriaTransfer.iterableProductOfferServicesConditions.isActiveConcreteProduct` to filter by active concrete products.
     * - Uses `IterableProductOfferServicesCriteriaTransfer.iterableProductOfferServicesConditions.isActiveService` to filter by active services.
     * - Uses `IterableProductOfferServicesCriteriaTransfer.iterableProductOfferServicesConditions.isActiveServicePoint` to filter by active service points.
     * - Uses `IterableProductOfferServicesCriteriaTransfer.iterableProductOfferServicesConditions.withServicePointRelations` to load service point relations.
     * - Returns a generator to get a list of `ProductOfferServicesTransfer`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\IterableProductOfferServicesCriteriaTransfer $iterableProductOfferServicesCriteriaTransfer
     *
     * @return iterable<list<\Generated\Shared\Transfer\ProductOfferServicesTransfer>>
     */
    public function iterateProductOfferServices(
        IterableProductOfferServicesCriteriaTransfer $iterableProductOfferServicesCriteriaTransfer
    ): iterable;
}
