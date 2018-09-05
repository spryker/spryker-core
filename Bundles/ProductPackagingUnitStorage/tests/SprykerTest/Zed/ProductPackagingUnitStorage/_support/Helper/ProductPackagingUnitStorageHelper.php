<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductPackagingUnitStorage\Helper;

use Codeception\Module;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\ProductPackagingUnitStorage\Persistence\SpyProductAbstractPackagingStorageQuery;

class ProductPackagingUnitStorageHelper extends Module
{
    /**
     * @param string $sku
     *
     * @return bool
     */
    public function isProductAbstractCreated(string $sku): bool
    {
        $productAbstractQuery = $this->getPropelProductAbstractQuery();
        $productAbstractQuery->filterBySku($sku);

        return $productAbstractQuery->exists();
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    protected function getPropelProductAbstractQuery(): SpyProductAbstractQuery
    {
        return SpyProductAbstractQuery::create();
    }

    /**
     * @return void
     */
    public function assertStorageDatabaseTableIsEmpty(): void
    {
        $query = SpyProductAbstractPackagingStorageQuery::create();
        $query->deleteAll();
    }
}
