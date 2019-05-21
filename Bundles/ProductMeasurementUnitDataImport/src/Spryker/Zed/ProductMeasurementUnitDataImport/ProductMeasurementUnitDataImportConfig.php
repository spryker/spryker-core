<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductMeasurementUnitDataImport;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Spryker\Zed\DataImport\DataImportConfig;

class ProductMeasurementUnitDataImportConfig extends DataImportConfig
{
    public const IMPORT_TYPE_PRODUCT_MEASUREMENT_UNIT = 'product-measurement-unit';
    public const IMPORT_TYPE_PRODUCT_MEASUREMENT_BASE_UNIT = 'product-measurement-base-unit';
    public const IMPORT_TYPE_PRODUCT_MEASUREMENT_SALES_UNIT = 'product-measurement-sales-unit';
    public const IMPORT_TYPE_PRODUCT_MEASUREMENT_SALES_UNIT_STORE = 'product-measurement-sales-unit-store';

    /**
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getProductMeasurementUnitDataImportConfiguration(): DataImporterConfigurationTransfer
    {
        $moduleDataImportDirectory = $this->getModuleDataImportDirectory();

        return $this->buildImporterConfiguration($moduleDataImportDirectory . 'product_measurement_unit.csv', static::IMPORT_TYPE_PRODUCT_MEASUREMENT_UNIT);
    }

    /**
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getProductMeasurementBaseUnitDataImportConfiguration(): DataImporterConfigurationTransfer
    {
        $moduleDataImportDirectory = $this->getModuleDataImportDirectory();

        return $this->buildImporterConfiguration($moduleDataImportDirectory . 'product_measurement_base_unit.csv', static::IMPORT_TYPE_PRODUCT_MEASUREMENT_BASE_UNIT);
    }

    /**
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getProductMeasurementSalesUnitDataImportConfiguration(): DataImporterConfigurationTransfer
    {
        $moduleDataImportDirectory = $this->getModuleDataImportDirectory();

        return $this->buildImporterConfiguration($moduleDataImportDirectory . 'product_measurement_sales_unit.csv', static::IMPORT_TYPE_PRODUCT_MEASUREMENT_SALES_UNIT);
    }

    /**
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getProductMeasurementSalesUnitStoreDataImportConfiguration(): DataImporterConfigurationTransfer
    {
        $moduleDataImportDirectory = $this->getModuleDataImportDirectory();

        return $this->buildImporterConfiguration($moduleDataImportDirectory . 'product_measurement_sales_unit_store.csv', static::IMPORT_TYPE_PRODUCT_MEASUREMENT_SALES_UNIT_STORE);
    }

    /**
     * @return string
     */
    protected function getModuleRoot(): string
    {
        $moduleRoot = realpath(
            __DIR__
            . DIRECTORY_SEPARATOR . '..'
            . DIRECTORY_SEPARATOR . '..'
            . DIRECTORY_SEPARATOR . '..'
            . DIRECTORY_SEPARATOR . '..'
        );

        return $moduleRoot . DIRECTORY_SEPARATOR;
    }

    /**
     * @return string
     */
    protected function getModuleDataImportDirectory(): string
    {
        $moduleDataImportDirectory = $this->getModuleRoot() . 'data' . DIRECTORY_SEPARATOR . 'import' . DIRECTORY_SEPARATOR;

        return $moduleDataImportDirectory;
    }
}
