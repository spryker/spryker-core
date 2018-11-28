<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListStorage\Persistence\Mapper;

use Generated\Shared\Transfer\SpyProductAbstractProductListStorageEntityTransfer;
use Generated\Shared\Transfer\SpyProductConcreteProductListStorageEntityTransfer;
use Orm\Zed\ProductListStorage\Persistence\SpyProductAbstractProductListStorage;
use Orm\Zed\ProductListStorage\Persistence\SpyProductConcreteProductListStorage;

interface ProductListStorageMapperInterface
{
    /**
     * @param \Orm\Zed\ProductListStorage\Persistence\SpyProductConcreteProductListStorage $productConcreteProductListStorageEntity
     * @param \Generated\Shared\Transfer\SpyProductConcreteProductListStorageEntityTransfer $productConcreteProductListStorageEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpyProductConcreteProductListStorageEntityTransfer
     */
    public function mapProductConcreteProductListStorageEntitiesToTransfers(
        SpyProductConcreteProductListStorage $productConcreteProductListStorageEntity,
        SpyProductConcreteProductListStorageEntityTransfer $productConcreteProductListStorageEntityTransfer
    ): SpyProductConcreteProductListStorageEntityTransfer;

    /**
     * @param \Orm\Zed\ProductListStorage\Persistence\SpyProductAbstractProductListStorage $productAbstractProductListStorageEntity
     * @param \Generated\Shared\Transfer\SpyProductAbstractProductListStorageEntityTransfer $productAbstractProductListStorageEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpyProductAbstractProductListStorageEntityTransfer
     */
    public function mapProductAbstractProductListStorageEntitiesToTransfers(
        SpyProductAbstractProductListStorage $productAbstractProductListStorageEntity,
        SpyProductAbstractProductListStorageEntityTransfer $productAbstractProductListStorageEntityTransfer
    ): SpyProductAbstractProductListStorageEntityTransfer;
}
