<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\CompanyDataImport\Helper;

use Codeception\Module;
use Orm\Zed\Company\Persistence\SpyCompanyQuery;

class CompanyDataImportHelper extends Module
{
    /**
     * @return void
     */
    public function assertDatabaseTableContainsData(): void
    {
        $companyQuery = $this->getCompanyQuery();
        $this->assertTrue(($companyQuery->count() > 0), 'Expected at least one entry in the database table but database table is empty.');
    }

    /**
     * @return \Orm\Zed\Company\Persistence\SpyCompanyQuery
     */
    protected function getCompanyQuery(): SpyCompanyQuery
    {
        return SpyCompanyQuery::create();
    }
}
