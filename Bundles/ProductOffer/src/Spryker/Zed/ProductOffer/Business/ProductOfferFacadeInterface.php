<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOffer\Business;

use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;

interface ProductOfferFacadeInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer|null $productOfferCriteriaFilter
     *
     * @return \Generated\Shared\Transfer\ProductOfferCollectionTransfer
     */
    public function find(?ProductOfferCriteriaFilterTransfer $productOfferCriteriaFilter): ProductOfferCollectionTransfer;

    /**
     * Specification:
     * - Finds ProductOfferTransfer by provided ProductOfferCriteriaFilterTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer|null $productOfferCriteriaFilter
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer|null
     */
    public function findOne(?ProductOfferCriteriaFilterTransfer $productOfferCriteriaFilter): ?ProductOfferTransfer;

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferCriteriaFilter
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    public function create(ProductOfferTransfer $productOfferCriteriaFilter): ProductOfferTransfer;
}
