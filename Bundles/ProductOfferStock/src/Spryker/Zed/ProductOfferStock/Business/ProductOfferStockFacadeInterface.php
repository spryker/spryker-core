<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferStock\Business;

use ArrayObject;
use Generated\Shared\Transfer\ProductOfferStockRequestTransfer;
use Generated\Shared\Transfer\ProductOfferStockResultTransfer;
use Generated\Shared\Transfer\ProductOfferStockTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;

interface ProductOfferStockFacadeInterface
{
    /**
     * Specification:
     * - Provides resulting data of all the stocks for provided store and productOfferReference.
     * - Expects ProductOfferStockRequestTransfer.store to be provided.
     * - Expects ProductOfferStockRequestTransfer.productOfferReference to be provided.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferStockRequestTransfer $productOfferStockRequestTransfer
     *
     * @throws \Spryker\Zed\ProductOfferStock\Business\Exception\ProductOfferNotFoundException
     *
     * @return \Generated\Shared\Transfer\ProductOfferStockResultTransfer
     */
    public function getProductOfferStockResult(
        ProductOfferStockRequestTransfer $productOfferStockRequestTransfer
    ): ProductOfferStockResultTransfer;

    /**
     * Specification:
     * - Retrieves product offer stocks from database for provided productOfferReference.
     * - Expects ProductOfferStockRequestTransfer.productOfferReference to be provided.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferStockRequestTransfer $productOfferStockRequestTransfer
     *
     * @throws \Spryker\Zed\ProductOfferStock\Business\Exception\ProductOfferNotFoundException
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ProductOfferStockTransfer[]
     */
    public function getProductOfferStocks(ProductOfferStockRequestTransfer $productOfferStockRequestTransfer): ArrayObject;

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
