<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\CompanyBusinessUnitDataImport\Helper;

use Codeception\Module;
use Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnitQuery;

class CompanyBusinessUnitDataImportHelper extends Module
{
    /**
     * @return void
     */
    public function assertDatabaseTableIsEmpty(): void
    {
        $companyQuery = $this->getCompanyBusinessUnitQuery();
        $this->assertCount(
            0,
            $companyQuery,
            'Found at least one entry in the database table but database table was expected to be empty.'
        );
    }

    /**
     * @return void
     */
    public function assertDatabaseTableContainsData(): void
    {
        $companyQuery = $this->getCompanyBusinessUnitQuery();
        $this->assertGreaterThan(
            0,
            $companyQuery->count(),
            'Expected at least one entry in the database table but database table is empty.'
        );
    }

    /**
     * @return \Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnitQuery
     */
    protected function getCompanyBusinessUnitQuery(): SpyCompanyBusinessUnitQuery
    {
        return SpyCompanyBusinessUnitQuery::create();
    }
}
