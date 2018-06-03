<?php
namespace SprykerTest\Zed\ProductPackagingUnitDataImport\Helper;

use Codeception\Module;
use Generated\Shared\Transfer\SpyProductPackagingUnitTypeEntityTransfer;
use Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitTypeQuery;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;

class ProductPackagingUnitTypeDataImportHelper extends Module
{
    use DataCleanupHelperTrait;

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
        $query = $this->getProductPackagingUnitTypeQuery();
        $this->assertEquals(0, $query->count(), 'Found at least one entry in the database table but database table `product_packaging_unit_type` was expected to be empty.');
    }

    /**
     * @return void
     */
    public function assertProductPackagingUnitTypeTableHasRecords(): void
    {
        $query = $this->getProductPackagingUnitTypeQuery();
        $this->assertTrue($query->count() > 0, 'Expected at least one entry in the database table `product_packaging_unit_type` but database table is empty.');
    }

    /**
     * @param array $override
     *
     * @return \Generated\Shared\Transfer\SpyProductPackagingUnitTypeEntityTransfer
     */
    public function haveProductPackagingUnitType(array $override = []): SpyProductPackagingUnitTypeEntityTransfer
    {
        $productPackagingUnitTypeTransfer = (new SpyProductPackagingUnitTypeEntityTransfer());
        $productPackagingUnitTypeTransfer->fromArray($override, true);

        $productPackagingUnitTypeEntity = $this->storeProductPackagingUnitType($productPackagingUnitTypeTransfer);

        $this->getDataCleanupHelper()->_addCleanup(function () use ($productPackagingUnitTypeEntity) {
            $this->cleanupProductPackagingUnitType($productPackagingUnitTypeEntity);
        });

        return $productPackagingUnitTypeEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductPackagingUnitTypeEntityTransfer $productPackagingUnitTypeEntity
     *
     * @return \Generated\Shared\Transfer\SpyProductPackagingUnitTypeEntityTransfer
     */
    protected function storeProductPackagingUnitType(SpyProductPackagingUnitTypeEntityTransfer $productPackagingUnitTypeEntity)
    {
        $spyProductPackagingUnitTypeEntity = $this->getProductPackagingUnitTypeQuery()
            ->filterByName($productPackagingUnitTypeEntity->getName())
            ->findOneOrCreate();

        $spyProductPackagingUnitTypeEntity->save();

        $this->debug(sprintf('Inserted product packaging unit type with name: %s', $productPackagingUnitTypeEntity->getName()));

        $productPackagingUnitTypeEntity->fromArray($spyProductPackagingUnitTypeEntity->toArray(), true);

        return $productPackagingUnitTypeEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductPackagingUnitTypeEntityTransfer $productPackagingUnitTypeEntity
     *
     * @return void
     */
    protected function cleanupProductPackagingUnitType(SpyProductPackagingUnitTypeEntityTransfer $productPackagingUnitTypeEntity)
    {
        $this->debug(sprintf('Deleting product packaging unit with name: %s', $productPackagingUnitTypeEntity->getName()));

        $this->getProductPackagingUnitTypeQuery()
            ->findByName($productPackagingUnitTypeEntity->getIdProductPackagingUnitType())
            ->delete();
    }

    /**
     * @return \Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitTypeQuery
     */
    protected function getProductPackagingUnitTypeQuery(): SpyProductPackagingUnitTypeQuery
    {
        return SpyProductPackagingUnitTypeQuery::create();
    }
}
