<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\ProductMeasurementUnitDataImport\Helper;

use Codeception\Module;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Orm\Zed\ProductMeasurementUnit\Persistence\SpyProductMeasurementBaseUnitQuery;
use Spryker\Zed\ProductMeasurementUnitDataImport\Communication\Plugin\ProductMeasurementBaseUnitDataImportPlugin;

class ProductMeasurementBaseUnitDataImportHelper extends Module
{
    /**
     * @return void
     */
    public function ensureMeasurementBaseUnitIsEmpty(): void
    {
        $query = $this->getProductMeasurementBaseUnitQuery();
        $query->deleteAll();
    }

    /**
     * @return void
     */
    public function assertMeasurementBaseUnitIsEmpty(): void
    {
        $query = $this->getProductMeasurementBaseUnitQuery();
        $this->assertCount(0, $query, 'Found at least one entry in the database table but database table was expected to be empty.');
    }

    /**
     * @return void
     */
    public function assertMeasurementBaseUnitContainsData(): void
    {
        $query = $this->getProductMeasurementBaseUnitQuery();
        $this->assertTrue(($query->count() > 0), 'Expected at least one entry in the database table but database table is empty.');
    }

    /**
     * @return \Orm\Zed\ProductMeasurementUnit\Persistence\SpyProductMeasurementBaseUnitQuery
     */
    protected function getProductMeasurementBaseUnitQuery(): SpyProductMeasurementBaseUnitQuery
    {
        return SpyProductMeasurementBaseUnitQuery::create();
    }

    /**
     * @param string $dataDir
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    public function importMeasurementBaseUnitData(string $dataDir): DataImporterReportTransfer
    {
        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName($dataDir . 'import/product_measurement_base_unit.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $productMeasurementBaseUnitDataImportPlugin = new ProductMeasurementBaseUnitDataImportPlugin();

        return $productMeasurementBaseUnitDataImportPlugin->import($dataImportConfigurationTransfer);
    }
}
