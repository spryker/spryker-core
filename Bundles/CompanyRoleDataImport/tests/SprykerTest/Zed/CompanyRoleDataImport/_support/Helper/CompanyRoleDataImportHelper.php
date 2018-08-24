<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\CompanyRoleDataImport\Helper;

use Codeception\Module;
use Orm\Zed\CompanyRole\Persistence\Map\SpyCompanyRoleTableMap;
use Orm\Zed\CompanyRole\Persistence\SpyCompanyRoleQuery;

class CompanyRoleDataImportHelper extends Module
{
    protected const ERROR_MESSAGE_FOUND = 'Found at least one entry in the database table but database table `%s` was expected to be empty.';

    protected const ERROR_MESSAGE_EXPECTED = 'Expected at least one entry in the database table `%s` but table is empty.';

    /**
     * @return void
     */
    public function truncateCompanyRoles(): void
    {
        $this->getCompanyRoleQuery()
            ->deleteAll();
    }

    /**
     * @return void
     */
    public function assertCompanyRoleTableIsEmtpy(): void
    {
        $this->assertFalse($this->getCompanyRoleQuery()->exists(), sprintf(static::ERROR_MESSAGE_FOUND, SpyCompanyRoleTableMap::TABLE_NAME));
    }

    /**
     * @return void
     */
    public function assertCompanyRoleTableHasRecords(): void
    {
        $this->assertTrue($this->getCompanyRoleQuery()->exists(), sprintf(static::ERROR_MESSAGE_EXPECTED, SpyCompanyRoleTableMap::TABLE_NAME));
    }

    /**
     * @return \Orm\Zed\CompanyRole\Persistence\SpyCompanyRoleQuery
     */
    protected function getCompanyRoleQuery(): SpyCompanyRoleQuery
    {
        return SpyCompanyRoleQuery::create();
    }
}
