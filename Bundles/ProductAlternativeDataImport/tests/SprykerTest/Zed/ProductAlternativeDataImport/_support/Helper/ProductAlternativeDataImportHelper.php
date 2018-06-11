<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductAlternativeDataImport\DataImport\Helper;

use Codeception\Module;
use Orm\Zed\ProductAlternative\Persistence\SpyProductAlternativeQuery;

class ProductAlternativeDataImportHelper extends Module
{
    /**
     * @return void
     */
    public function ensureDatabaseTableIsEmpty(): void
    {
        $query = $this->createProductAlternativeQuery();
        $query->deleteAll();
    }

    /**
     * @return void
     */
    public function assertDatabaseTableContainsData(): void
    {
        $query = $this->createProductAlternativeQuery();
        $this->assertTrue(($query->count() > 0), 'Expected at least one entry in the database table but database table is empty.');
    }

    /**
     * @return \Orm\Zed\ProductAlternative\Persistence\SpyProductAlternativeQuery
     */
    protected function createProductAlternativeQuery(): SpyProductAlternativeQuery
    {
        return SpyProductAlternativeQuery::create();
    }
}
