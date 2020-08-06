<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantStockDataImport\Helper;

use Codeception\Module;
use Orm\Zed\MerchantStock\Persistence\SpyMerchantStockQuery;

class MerchantStockDataImportHelper extends Module
{
    /**
     * @return void
     */
    public function ensureDatabaseTableIsEmpty(): void
    {
        SpyMerchantStockQuery::create()->deleteAll();
    }

    /**
     * @return void
     */
    public function assertDatabaseTableContainsData(): void
    {
        $merchantStockQuery = SpyMerchantStockQuery::create();

        $this->assertTrue(
            $merchantStockQuery->count() > 0,
            'Expected at least one entry in the database table but database table is empty.'
        );
    }
}
