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
use Orm\Zed\ProductMeasurementUnit\Persistence\SpyProductMeasurementSalesUnitQuery;
use Spryker\Zed\ProductMeasurementUnitDataImport\Communication\Plugin\ProductMeasurementSalesUnitDataImportPlugin;

class ProductMeasurementSalesUnitDataImportHelper extends Module
{
    /**
     * @return void
     */
    public function ensureMeasurementSalesUnitIsEmpty(): void
    {
        $query = $this->getProductMeasurementSalesUnitQuery();
        $query->deleteAll();
    }

    /**
     * @return void
     */
    public function assertMeasurementSalesUnitIsEmpty(): void
    {
        $query = $this->getProductMeasurementSalesUnitQuery();
        $this->assertCount(0, $query, 'Found at least one entry in the database table but database table was expected to be empty.');
    }

    /**
     * @return void
     */
    public function assertMeasurementSalesUnitContainsData(): void
    {
        $query = $this->getProductMeasurementSalesUnitQuery();
        $this->assertTrue(($query->count() > 0), 'Expected at least one entry in the database table but database table is empty.');
    }

    /**
     * @return \Orm\Zed\ProductMeasurementUnit\Persistence\SpyProductMeasurementSalesUnitQuery
     */
    protected function getProductMeasurementSalesUnitQuery(): SpyProductMeasurementSalesUnitQuery
    {
        return SpyProductMeasurementSalesUnitQuery::create();
    }

    /**
     * @param string $dataDir
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    public function importMeasurementSalesUnitData($dataDir): DataImporterReportTransfer
    {
        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName($dataDir . 'import/product_measurement_sales_unit.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $productMeasurementSalesUnitDataImportPlugin = new ProductMeasurementSalesUnitDataImportPlugin();

        return $productMeasurementSalesUnitDataImportPlugin->import($dataImportConfigurationTransfer);
    }
}
