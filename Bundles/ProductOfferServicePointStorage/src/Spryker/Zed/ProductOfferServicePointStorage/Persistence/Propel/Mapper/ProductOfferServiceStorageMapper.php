<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePointStorage\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ProductOfferServiceStorageTransfer;
use Orm\Zed\ProductOfferServicePointStorage\Persistence\SpyProductOfferServiceStorage;

class ProductOfferServiceStorageMapper
{
    /**
     * @param \Generated\Shared\Transfer\ProductOfferServiceStorageTransfer $productOfferServiceStorageTransfer
     * @param \Orm\Zed\ProductOfferServicePointStorage\Persistence\SpyProductOfferServiceStorage $productOfferServiceStorageEntity
     *
     * @return \Orm\Zed\ProductOfferServicePointStorage\Persistence\SpyProductOfferServiceStorage
     */
    public function mapProductOfferServiceStorageTransferToProductOfferServiceStorageEntity(
        ProductOfferServiceStorageTransfer $productOfferServiceStorageTransfer,
        SpyProductOfferServiceStorage $productOfferServiceStorageEntity
    ): SpyProductOfferServiceStorage {
        return $productOfferServiceStorageEntity->setData($productOfferServiceStorageTransfer->toArray());
    }
}
