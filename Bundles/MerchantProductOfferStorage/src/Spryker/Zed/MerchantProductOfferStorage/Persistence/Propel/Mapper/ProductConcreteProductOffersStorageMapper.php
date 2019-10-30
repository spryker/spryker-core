<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferStorage\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ProductConcreteProductOffersStorageTransfer;
use Orm\Zed\MerchantProductOfferStorage\Persistence\SpyProductConcreteProductOffersStorage;

class ProductConcreteProductOffersStorageMapper implements ProductConcreteProductOffersStorageMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductConcreteProductOffersStorageTransfer $productConcreteProductOffersStorageTransfer
     * @param \Orm\Zed\MerchantProductOfferStorage\Persistence\SpyProductConcreteProductOffersStorage $productConcreteProductOffersStorageEntity
     *
     * @return \Orm\Zed\MerchantProductOfferStorage\Persistence\SpyProductConcreteProductOffersStorage
     */
    public function mapProductConcreteProductOffersStorageTransferToProductConcreteProductOffersStorageEntity(
        ProductConcreteProductOffersStorageTransfer $productConcreteProductOffersStorageTransfer,
        SpyProductConcreteProductOffersStorage $productConcreteProductOffersStorageEntity
    ): SpyProductConcreteProductOffersStorage {
        $productConcreteProductOffersStorageEntity->fromArray(
            $productConcreteProductOffersStorageTransfer->modifiedToArray(false)
        );

        return $productConcreteProductOffersStorageEntity;
    }

    /**
     * @param \Orm\Zed\MerchantProductOfferStorage\Persistence\SpyProductConcreteProductOffersStorage $productConcreteProductOffersStorageEntity
     * @param \Generated\Shared\Transfer\ProductConcreteProductOffersStorageTransfer $productConcreteProductOffersStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteProductOffersStorageTransfer
     */
    public function mapProductConcreteProductOffersStorageEntityToProductConcreteProductOffersStorageTransfer(
        SpyProductConcreteProductOffersStorage $productConcreteProductOffersStorageEntity,
        ProductConcreteProductOffersStorageTransfer $productConcreteProductOffersStorageTransfer
    ): ProductConcreteProductOffersStorageTransfer {
        $productConcreteProductOffersStorageTransfer = $productConcreteProductOffersStorageTransfer->fromArray(
            $productConcreteProductOffersStorageEntity->toArray(),
            true
        );

        return $productConcreteProductOffersStorageTransfer;
    }
}
