<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\ProductQuantityDataImport\Helper;

use Codeception\Module;
use Orm\Zed\ProductQuantity\Persistence\SpyProductQuantityQuery;

class ProductQuantityDataImportHelper extends Module
{
    /**
     * @return void
     */
    public function ensureDatabaseTableIsEmpty(): void
    {
        $query = $this->getProductQuantityQuery();
        $query->deleteAll();
    }

    /**
     * @return void
     */
    public function assertDatabaseTableIsEmpty(): void
    {
        $query = $this->getProductQuantityQuery();
        $this->assertCount(0, $query, 'Found at least one entry in the database table but database table was expected to be empty.');
    }

    /**
     * @return void
     */
    public function assertDatabaseTableContainsData(): void
    {
        $query = $this->getProductQuantityQuery();
        $this->assertTrue(($query->count() > 0), 'Expected at least one entry in the database table but database table is empty.');
    }

    /**
     * @return \Orm\Zed\ProductQuantity\Persistence\SpyProductQuantityQuery
     */
    protected function getProductQuantityQuery(): SpyProductQuantityQuery
    {
        return SpyProductQuantityQuery::create();
    }
}
