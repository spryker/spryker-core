<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferStorage\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ProductOfferStorageTransfer;
use Orm\Zed\MerchantProductOfferStorage\Persistence\SpyProductOfferStorage;

class ProductOfferStorageMapper implements ProductOfferStorageMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductOfferStorageTransfer $productOfferStorageTransfer
     * @param \Orm\Zed\MerchantProductOfferStorage\Persistence\SpyProductOfferStorage $spyProductOfferStorage
     *
     * @return \Orm\Zed\MerchantProductOfferStorage\Persistence\SpyProductOfferStorage
     */
    public function mapProductOfferStorageTransferToProductOfferStorageEntity(
        ProductOfferStorageTransfer $productOfferStorageTransfer,
        SpyProductOfferStorage $spyProductOfferStorage
    ): SpyProductOfferStorage {
        $spyProductOfferStorage->fromArray(
            $productOfferStorageTransfer->modifiedToArray(false)
        );

        return $spyProductOfferStorage;
    }

    /**
     * @param \Orm\Zed\MerchantProductOfferStorage\Persistence\SpyProductOfferStorage $spyProductOfferStorage
     * @param \Generated\Shared\Transfer\ProductOfferStorageTransfer $productOfferStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferStorageTransfer
     */
    public function mapProductOfferStorageEntityToProductOfferStorageTransfer(
        SpyProductOfferStorage $spyProductOfferStorage,
        ProductOfferStorageTransfer $productOfferStorageTransfer
    ): ProductOfferStorageTransfer {
        $merchantTransfer = $productOfferStorageTransfer->fromArray(
            $spyProductOfferStorage->toArray(),
            true
        );

        return $merchantTransfer;
    }
}
