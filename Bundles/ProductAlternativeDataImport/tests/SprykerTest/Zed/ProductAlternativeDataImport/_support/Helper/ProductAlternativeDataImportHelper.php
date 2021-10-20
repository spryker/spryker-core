<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
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
        $query->find()->delete();
    }

    /**
     * @return void
     */
    public function assertDatabaseTableContainsData(): void
    {
        $query = $this->getProductAlternativeQuery();
        $this->assertGreaterThan(
            0,
            $query->count(),
            'Expected at least one entry in the database table but database table is empty.',
        );
    }

    /**
     * @return \Orm\Zed\ProductAlternative\Persistence\SpyProductAlternativeQuery
     */
    protected function getProductAlternativeQuery(): SpyProductAlternativeQuery
    {
        return SpyProductAlternativeQuery::create();
    }
}
