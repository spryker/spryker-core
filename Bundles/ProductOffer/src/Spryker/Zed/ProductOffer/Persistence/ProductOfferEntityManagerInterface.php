<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOffer\Persistence;

use Generated\Shared\Transfer\ProductOfferTransfer;

interface ProductOfferEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    public function createProductOffer(ProductOfferTransfer $productOfferTransfer): ProductOfferTransfer;

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    public function updateProductOffer(ProductOfferTransfer $productOfferTransfer): ProductOfferTransfer;

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    public function createProductOfferStores(ProductOfferTransfer $productOfferTransfer): ProductOfferTransfer;

    /**
     * @param int $idProductOffer
     * @param array $storeIds
     *
     * @return void
     */
    public function deleteProductOfferStores(int $idProductOffer, array $storeIds): void;
}
