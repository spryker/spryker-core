<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyUnitAddressLabelDataImport\Helper;

use Codeception\Module;
use Orm\Zed\CompanyUnitAddressLabel\Persistence\SpyCompanyUnitAddressLabelQuery;

class CompanyUnitAddressLabelDataImportHelper extends Module
{
    /**
     * @return void
     */
    public function ensureDatabaseTableIsEmpty(): void
    {
        $companyUnitAddressLabelQuery = $this->getCompanyUnitAddressLabelQuery();
        $companyUnitAddressLabelQuery->find()->delete();
    }

    /**
     * @return void
     */
    public function assertDatabaseTableIsEmpty(): void
    {
        $companyUnitAddressLabelQuery = $this->getCompanyUnitAddressLabelQuery();
        $this->assertCount(0, $companyUnitAddressLabelQuery, 'Found at least one entry in the database table but database table was expected to be empty.');
    }

    /**
     * @return void
     */
    public function assertDatabaseTableContainsData(): void
    {
        $companyUnitAddressLabelQuery = $this->getCompanyUnitAddressLabelQuery();
        $this->assertTrue(($companyUnitAddressLabelQuery->count() > 0), 'Expected at least one entry in the database table but database table is empty.');
    }

    /**
     * @return \Orm\Zed\CompanyUnitAddressLabel\Persistence\SpyCompanyUnitAddressLabelQuery
     */
    protected function getCompanyUnitAddressLabelQuery(): SpyCompanyUnitAddressLabelQuery
    {
        return SpyCompanyUnitAddressLabelQuery::create();
    }
}
