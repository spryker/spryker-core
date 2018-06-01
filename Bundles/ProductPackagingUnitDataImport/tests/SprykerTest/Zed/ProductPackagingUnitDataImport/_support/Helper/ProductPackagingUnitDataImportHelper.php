<?php
namespace SprykerTest\Zed\ProductPackagingUnitDataImport\Helper;

use Codeception\Module;
use Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitQuery;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class ProductPackagingUnitDataImportHelper extends Module
{
    use LocatorHelperTrait;

    /**
     * @return void
     */
    public function ensureDatabaseTableIsEmpty(): void
    {
        $query = $this->getProductPackagingUnitQuery();
        $query->deleteAll();
    }

    /**
     * @return void
     */
    public function assertDatabaseTableIsEmpty(): void
    {
        $query = $this->getProductPackagingUnitQuery();
        $this->assertEquals(0, $query->count(), 'Found at least one entry in the database table but database table was expected to be empty.');
    }

    /**
     * @return void
     */
    public function assertDatabaseTableContainsData(): void
    {
        $query = $this->getProductPackagingUnitQuery();
        $this->assertTrue($query->count() > 0, 'Expected at least one entry in the database table but database table is empty.');
    }

    /**
     * @return \Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitQuery
     */
    protected function getProductPackagingUnitQuery(): SpyProductPackagingUnitQuery
    {
        return SpyProductPackagingUnitQuery::create();
    }
}
