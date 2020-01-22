<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferStorage\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ProductOfferStorageTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;

class ProductOfferStorageMapper implements ProductOfferStorageMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     * @param \Generated\Shared\Transfer\ProductOfferStorageTransfer $productOfferStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferStorageTransfer
     */
    public function mapProductOfferTransferToProductOfferStorageTransfer(
        ProductOfferTransfer $productOfferTransfer,
        ProductOfferStorageTransfer $productOfferStorageTransfer
    ): ProductOfferStorageTransfer {
        $productOfferStorageTransfer->setIdProductOffer($productOfferTransfer->getIdProductOffer());
        $productOfferStorageTransfer->setIdMerchant($productOfferTransfer->getFkMerchant());
        $productOfferStorageTransfer->setProductOfferReference($productOfferTransfer->getProductOfferReference());
        $productOfferStorageTransfer->setMerchantSku($productOfferTransfer->getMerchantSku());

        return $productOfferStorageTransfer;
    }
}
