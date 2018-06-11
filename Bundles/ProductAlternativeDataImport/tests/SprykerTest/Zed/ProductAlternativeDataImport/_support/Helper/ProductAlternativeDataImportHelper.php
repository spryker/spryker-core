<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductAlternativeDataImport\Helper;

use Codeception\Module;
use Orm\Zed\ProductAlternative\Persistence\SpyProductAlternativeQuery;

class ProductAlternativeDataImportHelper extends Module
{
    /**
     * @return void
     */
    public function ensureDatabaseTableIsEmpty(): void
    {
        $query = $this->getProductAlternativeQuery();
        $query->deleteAll();
    }

    /**
     * @return void
     */
    public function assertDatabaseTableContainsData(): void
    {
        $query = $this->getProductAlternativeQuery();
        $this->assertTrue(($query->count() > 0), 'Expected at least one entry in the database table but database table is empty.');
    }

    /**
     * @return \Orm\Zed\ProductAlternative\Persistence\SpyProductAlternativeQuery
     */
    protected function getProductAlternativeQuery(): SpyProductAlternativeQuery
    {
        return SpyProductAlternativeQuery::create();
    }
}
