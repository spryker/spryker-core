<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferStorage\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ProductOfferStorageTransfer;
use Orm\Zed\MerchantProductOfferStorage\Persistence\SpyProductOfferStorage;

interface ProductOfferStorageMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductOfferStorageTransfer $productOfferStorageTransfer
     * @param \Orm\Zed\MerchantProductOfferStorage\Persistence\SpyProductOfferStorage $productOfferStorageEntity
     *
     * @return \Orm\Zed\MerchantProductOfferStorage\Persistence\SpyProductOfferStorage
     */
    public function mapProductOfferStorageTransferToProductOfferStorageEntity(
        ProductOfferStorageTransfer $productOfferStorageTransfer,
        SpyProductOfferStorage $productOfferStorageEntity
    ): SpyProductOfferStorage;

    /**
     * @param \Orm\Zed\MerchantProductOfferStorage\Persistence\SpyProductOfferStorage $productOfferStorageEntity
     * @param \Generated\Shared\Transfer\ProductOfferStorageTransfer $productOfferStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferStorageTransfer
     */
    public function mapProductOfferStorageEntityToProductOfferStorageTransfer(
        SpyProductOfferStorage $productOfferStorageEntity,
        ProductOfferStorageTransfer $productOfferStorageTransfer
    ): ProductOfferStorageTransfer;
}
