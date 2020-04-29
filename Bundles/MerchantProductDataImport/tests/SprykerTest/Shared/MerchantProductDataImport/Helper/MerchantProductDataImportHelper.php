<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Shared\MerchantProductDataImport\Helper;

use Codeception\Module;
use Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery;

class MerchantProductDataImportHelper extends Module
{
    /**
     * @return void
     */
    public function assertMerchantProductAbstractDatabaseTablesContainsData(): void
    {
        $this->assertTrue(
            SpyMerchantProductAbstractQuery::create()->find()->count() > 0,
            'Expected at least one entry in the database table but database table is empty.'
        );
    }
}
