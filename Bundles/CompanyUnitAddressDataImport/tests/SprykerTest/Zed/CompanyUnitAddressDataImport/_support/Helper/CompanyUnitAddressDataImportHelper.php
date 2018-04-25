<?php

/**
 * Copyright © 2018-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyUnitAddressDataImport\Helper;

use Codeception\Module;
use Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddressQuery;

class CompanyUnitAddressDataImportHelper extends Module
{
    /**
     * @return void
     */
    public function ensureDatabaseTableIsEmpty(): void
    {
        $companyUnitAddressQuery = $this->getCompanyUnitAddressQuery();
        $companyUnitAddressQuery->find()->delete();
    }

    /**
     * @return void
     */
    public function assertDatabaseTableIsEmpty(): void
    {
        $companyUnitAddressQuery = $this->getCompanyUnitAddressQuery();
        $this->assertCount(0, $companyUnitAddressQuery, 'Found at least one entry in the database table but database table was expected to be empty.');
    }

    /**
     * @return void
     */
    public function assertDatabaseTableContainsData(): void
    {
        $companyUnitAddressQuery = $this->getCompanyUnitAddressQuery();
        $this->assertTrue(($companyUnitAddressQuery->count() > 0), 'Expected at least one entry in the database table but database table is empty.');
    }

    /**
     * @return \Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddressQuery
     */
    protected function getCompanyUnitAddressQuery(): SpyCompanyUnitAddressQuery
    {
        return SpyCompanyUnitAddressQuery::create();
    }
}
