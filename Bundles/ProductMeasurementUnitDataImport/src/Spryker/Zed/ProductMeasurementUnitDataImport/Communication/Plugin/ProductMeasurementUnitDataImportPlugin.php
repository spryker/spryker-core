<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductMeasurementUnitDataImport\Communication\Plugin;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\DataImport\Dependency\Plugin\DataImportPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductMeasurementUnitDataImport\ProductMeasurementUnitDataImportConfig;

/**
 * @method \Spryker\Zed\ProductMeasurementUnitDataImport\Business\ProductMeasurementUnitDataImportFacadeInterface getFacade()
 */
class ProductMeasurementUnitDataImportPlugin extends AbstractPlugin implements DataImportPluginInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    public function import(?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null): DataImporterReportTransfer
    {
        return $this->getFacade()->importProductMeasurementUnit($dataImporterConfigurationTransfer);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getImportType(): string
    {
        return ProductMeasurementUnitDataImportConfig::IMPORT_TYPE_PRODUCT_MEASUREMENT_UNIT;
    }
}
