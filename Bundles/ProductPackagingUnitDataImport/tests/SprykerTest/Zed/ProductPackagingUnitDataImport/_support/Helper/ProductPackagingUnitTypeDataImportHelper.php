<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductPackagingUnitDataImport\Helper;

use Codeception\Module;
use Orm\Zed\ProductPackagingUnit\Persistence\Map\SpyProductPackagingUnitTypeTableMap;
use Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitTypeQuery;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;

class ProductPackagingUnitTypeDataImportHelper extends Module
{
    use DataCleanupHelperTrait;

    protected const ERROR_MESSAGE_FOUND = 'Found at least one entry in the database table but database table `%s` was expected to be empty.';

    protected const ERROR_MESSAGE_EXPECTED = 'Expected at least one entry in the database table `%s` but database` table is empty.';

    /**
     * @return void
     */
    public function truncateProductPackagingUnitTypes(): void
    {
        $this->getProductPackagingUnitTypeQuery()
            ->deleteAll();
    }

    /**
     * @return void
     */
    public function assertProductPackagingUnitTypeTableIsEmtpy(): void
    {
        $this->assertFalse($this->getProductPackagingUnitTypeQuery()->exists(), sprintf(static::ERROR_MESSAGE_FOUND, SpyProductPackagingUnitTypeTableMap::TABLE_NAME));
    }

    /**
     * @return void
     */
    public function assertProductPackagingUnitTypeTableHasRecords(): void
    {
        $this->assertTrue($this->getProductPackagingUnitTypeQuery()->exists(), sprintf(static::ERROR_MESSAGE_EXPECTED, SpyProductPackagingUnitTypeTableMap::TABLE_NAME));
    }

    /**
     * @return \Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitTypeQuery
     */
    protected function getProductPackagingUnitTypeQuery(): SpyProductPackagingUnitTypeQuery
    {
        return SpyProductPackagingUnitTypeQuery::create();
    }
}
