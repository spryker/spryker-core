<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\PriceProductDataImport\Helper;

use Codeception\Module;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery;

class PriceProductDataImportHelper extends Module
{
    /**
     * @return void
     */
    public function ensureDatabaseTableIsEmpty(): void
    {
        $this->getPriceProductStoreQuery()->deleteAll();
    }

    /**
     * @return void
     */
    public function assertDatabaseTableContainsData(): void
    {
        $priceProductQuery = $this->getPriceProductStoreQuery();
        $this->assertTrue($priceProductQuery->exists(), 'Expected at least one entry in the database table but database table is empty.');
    }

    /**
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery
     */
    protected function getPriceProductStoreQuery(): SpyPriceProductStoreQuery
    {
        return SpyPriceProductStoreQuery::create();
    }
}
