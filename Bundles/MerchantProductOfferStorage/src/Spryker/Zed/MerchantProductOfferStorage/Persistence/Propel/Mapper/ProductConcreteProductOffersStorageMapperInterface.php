<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferStorage\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ProductConcreteProductOffersStorageTransfer;
use Orm\Zed\MerchantProductOfferStorage\Persistence\SpyProductConcreteProductOffersStorage;

interface ProductConcreteProductOffersStorageMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductConcreteProductOffersStorageTransfer $ProductConcreteProductOffersStorageTransfer
     * @param \Orm\Zed\MerchantProductOfferStorage\Persistence\SpyProductConcreteProductOffersStorage $spyProductConcreteProductOffersStorage
     *
     * @return \Orm\Zed\MerchantProductOfferStorage\Persistence\SpyProductConcreteProductOffersStorage
     */
    public function mapProductConcreteProductOffersStorageTransferToProductConcreteProductOffersStorageEntity(
        ProductConcreteProductOffersStorageTransfer $ProductConcreteProductOffersStorageTransfer,
        SpyProductConcreteProductOffersStorage $spyProductConcreteProductOffersStorage
    ): SpyProductConcreteProductOffersStorage;

    /**
     * @param \Orm\Zed\MerchantProductOfferStorage\Persistence\SpyProductConcreteProductOffersStorage $spyProductConcreteProductOffersStorage
     * @param \Generated\Shared\Transfer\ProductConcreteProductOffersStorageTransfer $ProductConcreteProductOffersStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteProductOffersStorageTransfer
     */
    public function mapProductConcreteProductOffersStorageEntityToProductConcreteProductOffersStorageTransfer(
        SpyProductConcreteProductOffersStorage $spyProductConcreteProductOffersStorage,
        ProductConcreteProductOffersStorageTransfer $ProductConcreteProductOffersStorageTransfer
    ): ProductConcreteProductOffersStorageTransfer;
}
