<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImageResourceAliasStorage\Persistence;

interface ProductImageResourceAliasStorageRepositoryInterface
{
    /**
     * @param int[] $productAbstractIds
     *
     * @return \Orm\Zed\ProductImageStorage\Persistence\SpyProductAbstractImageStorage[]
     */
    public function getProductAbstractImageStorageEntities(array $productAbstractIds): array;

    /**
     * @param int[] $productImageSetIds
     *
     * @return array
     */
    public function getProductAbstractImageSetsSkuList(array $productImageSetIds): array;

    /**
     * @param int[] $productConcreteIds
     *
     * @return \Orm\Zed\ProductImageStorage\Persistence\SpyProductConcreteImageStorage[]
     */
    public function getProductConcreteImageStorageEntities(array $productConcreteIds): array;

    /**
     * @param int[] $productConcreteIds
     *
     * @return array
     */
    public function getProductConcreteImageSetsSkuList(array $productConcreteIds): array;
}
