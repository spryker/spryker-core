<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\CompanyUserDataImport\Helper;

use Codeception\Module;
use Orm\Zed\CompanyUser\Persistence\Map\SpyCompanyUserTableMap;
use Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery;

class CompanyUserDataImportHelper extends Module
{
    protected const ERROR_MESSAGE_FOUND = 'Found at least one entry in the database table but database table `%s` was expected to be empty.';

    protected const ERROR_MESSAGE_EXPECTED = 'Expected at least one entry in the database table `%s` but table is empty.';

    /**
     * @return void
     */
    public function truncateCompanyUsers(): void
    {
        $this->getCompanyUserQuery()
            ->deleteAll();
    }

    /**
     * @return void
     */
    public function assertCompanyUserTableIsEmtpy(): void
    {
        $this->assertFalse($this->getCompanyUserQuery()->exists(), sprintf(static::ERROR_MESSAGE_FOUND, SpyCompanyUserTableMap::TABLE_NAME));
    }

    /**
     * @return void
     */
    public function assertCompanyUserTableHasRecords(): void
    {
        $this->assertTrue($this->getCompanyUserQuery()->exists(), sprintf(static::ERROR_MESSAGE_EXPECTED, SpyCompanyUserTableMap::TABLE_NAME));
    }

    /**
     * @return \Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery
     */
    protected function getCompanyUserQuery(): SpyCompanyUserQuery
    {
        return SpyCompanyUserQuery::create();
    }
}
