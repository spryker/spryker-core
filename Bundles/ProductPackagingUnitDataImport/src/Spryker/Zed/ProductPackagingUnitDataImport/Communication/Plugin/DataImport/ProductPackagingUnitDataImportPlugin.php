<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductPackagingUnitDataImport\Communication\Plugin\DataImport;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\DataImport\Dependency\Plugin\DataImportPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductPackagingUnitDataImport\ProductPackagingUnitDataImportConfig;

/**
 * @method \Spryker\Zed\ProductPackagingUnitDataImport\Business\ProductPackagingUnitDataImportFacadeInterface getFacade()
 */
class ProductPackagingUnitDataImportPlugin extends AbstractPlugin implements DataImportPluginInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    public function import(
        ?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null
    ): DataImporterReportTransfer {
        return $this->getFacade()->importProductPackagingUnits($dataImporterConfigurationTransfer);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getImportType(): string
    {
        return ProductPackagingUnitDataImportConfig::IMPORT_TYPE_PRODUCT_PACKAGING_UNIT;
    }
}
