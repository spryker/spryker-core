<?php

namespace SprykerTest\Zed\ProductPackagingUnitDataImport\Helper;

use Codeception\Module;
use Orm\Zed\ProductPackagingUnit\Persistence\Map\SpyProductPackagingLeadProductTableMap;
use Orm\Zed\ProductPackagingUnit\Persistence\Map\SpyProductPackagingUnitTableMap;
use Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingLeadProductQuery;
use Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitQuery;

class ProductPackagingUnitDataImportHelper extends Module
{
    protected const ERROR_MESSAGE_FOUND = 'Found at least one entry in the database table but database table `%s` was expected to be empty.';

    protected const ERROR_MESSAGE_EXPECTED = 'Expected at least one entry in the database table `%s` but database` table is empty.';

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
    public function truncateProductPackagingLeadProducts(): void
    {
        $this->getProductPackagingLeadProductQuery()
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
     * @return void
     */
    public function assertProductPackagingLeadProductTableIsEmtpy(): void
    {
        $this->assertFalse($this->getProductPackagingLeadProductQuery()->exists(), sprintf(static::ERROR_MESSAGE_FOUND, SpyProductPackagingLeadProductTableMap::TABLE_NAME));
    }

    /**
     * @return void
     */
    public function assertProductPackagingUnitTableHasRecords(): void
    {
        $this->assertTrue($this->getProductPackagingUnitQuery()->exists(), sprintf(static::ERROR_MESSAGE_EXPECTED, SpyProductPackagingUnitTableMap::TABLE_NAME));
    }

    /**
     * @return void
     */
    public function assertProductPackagingLeadProductTableHasRecords(): void
    {
        $this->assertTrue($this->getProductPackagingLeadProductQuery()->exists(), sprintf(static::ERROR_MESSAGE_EXPECTED, SpyProductPackagingLeadProductTableMap::TABLE_NAME));
    }

    /**
     * @param int $productAbstractId
     *
     * @return void
     */
    public function cleanupProductPackagingLeadProduct(int $productAbstractId): void
    {
        $this->debug(sprintf('Deleting product packaging unit lead product for AbstractProduct: %s', $productAbstractId));

        $this->getProductPackagingLeadProductQuery()
            ->findByFkProductAbstract($productAbstractId)
            ->delete();
    }

    /**
     * @return \Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitQuery
     */
    protected function getProductPackagingUnitQuery(): SpyProductPackagingUnitQuery
    {
        return SpyProductPackagingUnitQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingLeadProductQuery
     */
    protected function getProductPackagingLeadProductQuery(): SpyProductPackagingLeadProductQuery
    {
        return SpyProductPackagingLeadProductQuery::create();
    }
}
