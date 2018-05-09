<?php

/**
 * Copyright © 2018-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductMeasurementUnitDataImport\Helper;

use Codeception\Module;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Orm\Zed\ProductMeasurementUnit\Persistence\SpyProductMeasurementUnitQuery;
use Spryker\Zed\ProductMeasurementUnitDataImport\Communication\Plugin\ProductMeasurementUnitDataImportPlugin;

class ProductMeasurementUnitDataImportHelper extends Module
{
    /**
     * @return void
     */
    public function ensureProductMeasurementUnitIsEmpty(): void
    {
        $query = $this->getProductMeasurementUnitQuery();
        $query->deleteAll();
    }

    /**
     * @return void
     */
    public function assertProductMeasurementUnitIsEmpty(): void
    {
        $query = $this->getProductMeasurementUnitQuery();
        $this->assertCount(0, $query, 'Found at least one entry in the database table but database table was expected to be empty.');
    }

    /**
     * @return void
     */
    public function assertProductMeasurementUnitContainsData(): void
    {
        $query = $this->getProductMeasurementUnitQuery();
        $this->assertTrue(($query->count() > 0), 'Expected at least one entry in the database table but database table is empty.');
    }

    /**
     * @return \Orm\Zed\ProductMeasurementUnit\Persistence\SpyProductMeasurementUnitQuery
     */
    protected function getProductMeasurementUnitQuery(): SpyProductMeasurementUnitQuery
    {
        return SpyProductMeasurementUnitQuery::create();
    }

    /**
     * @param string $dataDir
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    public function importMeasurementUnitData($dataDir): DataImporterReportTransfer
    {
        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName($dataDir . 'import/product_measurement_unit.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $productMeasurementUnitDataImportPlugin = new ProductMeasurementUnitDataImportPlugin();

        return $productMeasurementUnitDataImportPlugin->import($dataImportConfigurationTransfer);
    }
}
