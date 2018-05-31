<?php

/**
 * Copyright © 2018-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProductMerchantRelationshipDataImport\Helper;

use Codeception\Module;
use Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnitQuery;
use Orm\Zed\PriceProductMerchantRelationship\Persistence\SpyPriceProductMerchantRelationshipQuery;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class PriceProductMerchantRelationshipDataImportHelper extends Module
{
    use LocatorHelperTrait;

    /**
     * @return void
     */
    public function ensureDatabaseTableIsEmpty(): void
    {
        $priceProductQuery = $this->getPriceProductMerchantRelationshipQuery();
        $priceProductQuery->deleteAll();
    }

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
