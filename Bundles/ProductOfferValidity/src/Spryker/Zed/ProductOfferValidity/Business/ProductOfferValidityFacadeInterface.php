<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferValidity\Business;

use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\ProductOfferValidityTransfer;

interface ProductOfferValidityFacadeInterface
{
    /**
     * Specification:
     * - Finds product offers that are about to become valid/invalid for the current time.
     * - Product offers that are about to become active will be activated in the database.
     * - Product offers that are about to become inactive will be deactivated in the database.
     *
     * @api
     *
     * @return void
     */
    public function updateProductOfferStatusByValidityDate(): void;

    /**
     * Specification:
     * - Persists new Product Offer Validity entity to database.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferValidityTransfer $productOfferValidityTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferValidityTransfer
     */
    public function create(ProductOfferValidityTransfer $productOfferValidityTransfer): ProductOfferValidityTransfer;

    /**
     * Specification:
     * - Updates existing Product Offer Validity entity in database.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferValidityTransfer $productOfferValidityTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferValidityTransfer
     */
    public function update(ProductOfferValidityTransfer $productOfferValidityTransfer): ProductOfferValidityTransfer;

    /**
     * Specification:
     * - Expands provided ProductOfferTransfer with Product Offer Validity.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    public function expandProductOfferWithProductOfferValidity(ProductOfferTransfer $productOfferTransfer): ProductOfferTransfer;

    /**
     * Specification:
     * - Finds product offer validity by provided product offer id.
     * - Returns ProductOfferValidity transfer object.
     *
     * @api
     *
     * @param int $idProductOffer
     *
     * @return \Generated\Shared\Transfer\ProductOfferValidityTransfer|null
     */
    public function findProductOfferValidityByIdProductOffer(int $idProductOffer): ?ProductOfferValidityTransfer;
}
