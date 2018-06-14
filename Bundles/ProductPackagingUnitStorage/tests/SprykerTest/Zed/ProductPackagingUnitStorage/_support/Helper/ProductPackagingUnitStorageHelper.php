<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductPackagingUnitStorage\Helper;

use Codeception\Module;
use Orm\Zed\ProductPackagingUnitStorage\Persistence\SpyProductAbstractPackagingStorageQuery;

class ProductPackagingUnitStorageHelper extends Module
{
    /**
     * @return void
     */
    public function ensureStorageDatabaseTableIsEmpty(): void
    {
        $query = SpyProductAbstractPackagingStorageQuery::create();
        $query->deleteAll();
    }
}
