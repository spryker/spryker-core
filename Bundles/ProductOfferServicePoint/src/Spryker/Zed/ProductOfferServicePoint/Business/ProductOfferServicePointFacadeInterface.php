<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePoint\Business;

use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferServiceCollectionRequestTransfer;
use Generated\Shared\Transfer\ProductOfferServiceCollectionResponseTransfer;

interface ProductOfferServicePointFacadeInterface
{
    /**
     * Specification:
     * - Requires `ProductOfferCollectionTransfer.productOffers` to be set.
     * - Requires `ProductOfferTransfer.productOfferReference` to be set.
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
}
