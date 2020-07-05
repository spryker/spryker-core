<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferStock\Business;

use Generated\Shared\Transfer\ProductOfferStockRequestTransfer;
use Generated\Shared\Transfer\ProductOfferStockTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;

interface ProductOfferStockFacadeInterface
{
    /**
     * Specification:
     * - Retrieves product offer stock from database for provided store.
     * - Expects ProductOfferStockRequestTransfer.store to be provided.
     * - Expects ProductOfferStockRequestTransfer.productOfferReference to be provided.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferStockRequestTransfer $productOfferStockRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferStockTransfer
     */
    public function getProductOfferStock(ProductOfferStockRequestTransfer $productOfferStockRequestTransfer): ProductOfferStockTransfer;

    /**
     * Specification:
     * - Persists new Product Offer Stock entity to database.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferStockTransfer $productOfferStockTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferStockTransfer
     */
    public function create(ProductOfferStockTransfer $productOfferStockTransfer): ProductOfferStockTransfer;

    /**
     * Specification:
     * - Updates existing Product Offer Stock entity in database.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferStockTransfer $productOfferStockTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferStockTransfer
     */
    public function update(ProductOfferStockTransfer $productOfferStockTransfer): ProductOfferStockTransfer;

    /**
     * Specification:
     * - Expands provided ProductOfferTransfer with Product Offer Stock transfers.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    public function expandProductOfferWithProductOfferStockCollection(ProductOfferTransfer $productOfferTransfer): ProductOfferTransfer;
}
