<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOffer\Business;

use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;

interface ProductOfferFacadeInterface
{
    /**
     * Specification:
     * - Returns collection of product offer by provided criteria.
     * - Pagination is controlled with page, maxPerPage, nbResults, previousPage, nextPage, firstIndex, lastIndex, firstPage and lastPage values.
     * - Result might be filtered with concreteSku(s), productOfferReference(s) values.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer $productOfferCriteriaFilter
     *
     * @return \Generated\Shared\Transfer\ProductOfferCollectionTransfer
     */
    public function find(ProductOfferCriteriaFilterTransfer $productOfferCriteriaFilter): ProductOfferCollectionTransfer;

    /**
     * Specification:
     * - Finds ProductOfferTransfer by provided ProductOfferCriteriaFilterTransfer.
     * - Result might be filtered with concreteSku(s), productOfferReference(s) values.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer $productOfferCriteriaFilter
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer|null
     */
    public function findOne(ProductOfferCriteriaFilterTransfer $productOfferCriteriaFilter): ?ProductOfferTransfer;

    /**
     * Specification:
     * - Creates a product offer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferCriteriaFilter
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    public function create(ProductOfferTransfer $productOfferCriteriaFilter): ProductOfferTransfer;
}
