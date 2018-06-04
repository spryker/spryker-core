<?php
namespace SprykerTest\Zed\ProductPackagingUnitDataImport\Helper;

use Codeception\Module;
use Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitQuery;

class ProductPackagingUnitDataImportHelper extends Module
{
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
        $query = $this->getProductPackagingUnitQuery();
        $this->assertFalse($query->exists(), 'Found at least one entry in the database table but database table `product_packaging_unit` was expected to be empty.');
    }

    /**
     * @return void
     */
    public function assertProductPackagingUnitTableHasRecords(): void
    {
        $query = $this->getProductPackagingUnitQuery();
        $this->assertTrue($query->exists(), 'Expected at least one entry in the database table `product_packaging_unit` but database` table is empty.');
    }

    /**
     * @return \Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitQuery
     */
    protected function getProductPackagingUnitQuery(): SpyProductPackagingUnitQuery
    {
        return SpyProductPackagingUnitQuery::create();
    }
}
