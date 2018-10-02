<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\PriceProductMerchantRelationshipDataImport\Helper;

use Codeception\Module;
use Orm\Zed\PriceProductMerchantRelationship\Persistence\SpyPriceProductMerchantRelationshipQuery;

class PriceProductMerchantRelationshipDataImportHelper extends Module
{
    /**
     * @return void
     */
    public function assertDatabaseTableIsEmpty(): void
    {
        $priceProductQuery = $this->getPriceProductMerchantRelationshipQuery();
        $this->assertFalse($priceProductQuery->exists(), 'Found at least one entry in the database table but database table was expected to be empty.');
    }

    /**
     * @return void
     */
    public function assertDatabaseTableContainsData(): void
    {
        $priceProductQuery = $this->getPriceProductMerchantRelationshipQuery();
        $this->assertTrue($priceProductQuery->exists(), 'Expected at least one entry in the database table but database table is empty.');
    }

    /**
     * @return \Orm\Zed\PriceProductMerchantRelationship\Persistence\SpyPriceProductMerchantRelationshipQuery
     */
    protected function getPriceProductMerchantRelationshipQuery(): SpyPriceProductMerchantRelationshipQuery
    {
        return SpyPriceProductMerchantRelationshipQuery::create();
    }
}
