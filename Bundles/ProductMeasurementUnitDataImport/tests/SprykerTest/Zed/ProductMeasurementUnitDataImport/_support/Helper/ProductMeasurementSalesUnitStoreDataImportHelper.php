<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductMeasurementUnitDataImport\Helper;

use Codeception\Module;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Orm\Zed\ProductMeasurementUnit\Persistence\SpyProductMeasurementSalesUnitStoreQuery;
use Spryker\Zed\ProductMeasurementUnitDataImport\Communication\Plugin\ProductMeasurementSalesUnitStoreDataImportPlugin;

class ProductMeasurementSalesUnitStoreDataImportHelper extends Module
{
    /**
     * @return void
     */
    public function ensureMeasurementSalesUnitStoreIsEmpty(): void
    {
        $query = $this->getProductMeasurementSalesUnitStoreQuery();
        $query->deleteAll();
    }

    /**
     * @return void
     */
    public function assertMeasurementSalesUnitStoreIsEmpty(): void
    {
        $query = $this->getProductMeasurementSalesUnitStoreQuery();
        $this->assertCount(0, $query, 'Found at least one entry in the database table but database table was expected to be empty.');
    }

    /**
     * @return void
     */
    public function assertMeasurementSalesUnitStoreContainsData(): void
    {
        $query = $this->getProductMeasurementSalesUnitStoreQuery();
        $this->assertTrue(($query->count() > 0), 'Expected at least one entry in the database table but database table is empty.');
    }

    /**
     * @return \Orm\Zed\ProductMeasurementUnit\Persistence\SpyProductMeasurementSalesUnitStoreQuery
     */
    protected function getProductMeasurementSalesUnitStoreQuery(): SpyProductMeasurementSalesUnitStoreQuery
    {
        return SpyProductMeasurementSalesUnitStoreQuery::create();
    }

    /**
     * @param string $dataDir
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    public function importMeasurementSalesUnitStoreData($dataDir): DataImporterReportTransfer
    {
        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName($dataDir . 'import/product_measurement_sales_unit_store.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $productMeasurementSalesUnitStoreDataImportPlugin = new ProductMeasurementSalesUnitStoreDataImportPlugin();

        return $productMeasurementSalesUnitStoreDataImportPlugin->import($dataImportConfigurationTransfer);
    }
}
