<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitStorage\Persistence;

use Generated\Shared\Transfer\ProductAbstractPackagingStorageTransfer;
use Orm\Zed\ProductPackagingUnitStorage\Persistence\SpyProductAbstractPackagingStorage;
use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface ProductPackagingUnitStorageQueryContainerInterface extends QueryContainerInterface
{
    /**
     * @api
     *
     * @param int $productAbstractId
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function queryPackageProductsByAbstractId(int $productAbstractId);

    /**
     * @api
     *
     * @param array $productAbstractIds
     *
     * @return \Orm\Zed\ProductPackagingUnitStorage\Persistence\SpyProductAbstractPackagingStorage[]
     */
    public function queryProductAbstractPackagingStorageByProductAbstractIds(array $productAbstractIds);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractPackagingStorageTransfer $productAbstractPackagingTransfer
     *
     * @return \Orm\Zed\ProductPackagingUnitStorage\Persistence\SpyProductAbstractPackagingStorage
     */
    public function createProductAbstractPackagingStorage(ProductAbstractPackagingStorageTransfer $productAbstractPackagingTransfer): SpyProductAbstractPackagingStorage;

    /**
     * @api
     *
     * @param \Orm\Zed\ProductPackagingUnitStorage\Persistence\SpyProductAbstractPackagingStorage $productAbstractPackagingStorageEntity
     *
     * @return void
     */
    public function deleteProductAbstractPackagingStorage(SpyProductAbstractPackagingStorage $productAbstractPackagingStorageEntity): void;

    /**
     * @api
     *
     * @param int $productAbstractId
     *
     * @return \Generated\Shared\Transfer\ProductAbstractPackagingStorageTransfer
     */
    public function getProductAbstractPackagingTransferByProductAbstractId(int $productAbstractId): ProductAbstractPackagingStorageTransfer;
}
