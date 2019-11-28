<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\ProductPackagingUnitDataImport\Helper;

use Codeception\Module;
use Orm\Zed\ProductPackagingUnit\Persistence\Map\SpyProductPackagingUnitTableMap;
use Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitQuery;

class ProductPackagingUnitDataImportHelper extends Module
{
    protected const ERROR_MESSAGE_FOUND = 'Found at least one entry in the database table but database table `%s` was expected to be empty.';

    protected const ERROR_MESSAGE_EXPECTED_COUNT = 'Expected exactly %d entries in the database table `%s`, but found %d.';

    /**
     * @return void
     */
    public function truncateProductPackagingUnits(): void
    {
        $this->getProductPackagingUnitQuery()
            ->deleteAll();
    }

    /**
     * @return void
     */
    public function assertProductPackagingUnitTableIsEmtpy(): void
    {
        $this->assertFalse($this->getProductPackagingUnitQuery()->exists(), sprintf(static::ERROR_MESSAGE_FOUND, SpyProductPackagingUnitTableMap::TABLE_NAME));
    }

    /**
     * @param int $expectedCount
     *
     * @return void
     */
    public function assertProductPackagingUnitTableHasRecords(int $expectedCount): void
    {
        $foundCount = $this->getProductPackagingUnitQuery()->count();
        $this->assertEquals($expectedCount, $foundCount, sprintf(static::ERROR_MESSAGE_EXPECTED_COUNT, $expectedCount, SpyProductPackagingUnitTableMap::TABLE_NAME, $foundCount));
    }

    /**
     * @param int $productConcreteId
     *
     * @return void
     */
    public function cleanupProductPackagingUnitProduct(int $productConcreteId): void
    {
        $this->debug(sprintf('Deleting product packaging unit for Product: %s', $productConcreteId));

        $this->getProductPackagingUnitQuery()
            ->findByFkProduct($productConcreteId)
            ->delete();
    }

    /**
     * @return \Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitQuery
     */
    protected function getProductPackagingUnitQuery(): SpyProductPackagingUnitQuery
    {
        return SpyProductPackagingUnitQuery::create();
    }
}
