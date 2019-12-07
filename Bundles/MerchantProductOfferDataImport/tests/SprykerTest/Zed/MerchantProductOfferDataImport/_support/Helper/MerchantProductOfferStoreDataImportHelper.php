<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\MerchantProductOfferDataImport\Helper;

use Codeception\Module;
use Orm\Zed\ProductOffer\Persistence\SpyProductOfferStoreQuery;

class MerchantProductOfferStoreDataImportHelper extends Module
{
    /**
     * @return void
     */
    public function assertProductOfferStoreDatabaseTableIsEmpty(): void
    {
        $query = $this->getProductOfferStorePropelQuery();

        $this->assertSame(0, $query->count(), 'Found at least one entry in the database table but database table was expected to be empty.');
    }

    /**
     * @return void
     */
    public function assertProductOfferStoreDatabaseTableContainsData(): void
    {
        $query = $this->getProductOfferStorePropelQuery();

        $this->assertTrue($query->count() > 0, 'Expected at least one entry in the database table but database table is empty.');
    }

    /**
     * @return \Orm\Zed\ProductOffer\Persistence\SpyProductOfferStoreQuery
     */
    protected function getProductOfferStorePropelQuery(): SpyProductOfferStoreQuery
    {
        return SpyProductOfferStoreQuery::create();
    }
}
