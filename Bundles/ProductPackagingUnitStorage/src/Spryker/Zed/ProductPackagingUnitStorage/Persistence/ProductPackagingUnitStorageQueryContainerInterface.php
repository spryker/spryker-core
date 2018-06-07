<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitStorage\Persistence;

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
    public function queryLeadProductByAbstractId(int $productAbstractId);

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
     * @return \Orm\Zed\ProductPackagingUnitStorage\Persistence\SpyProductAbstractPackagingStorageQuery
     */
    public function queryProductAbstractPackagingStorageByProductAbstractIds(array $productAbstractIds);
}
