<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListDataImport\Communication\Plugin;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\DataImport\Dependency\Plugin\DataImportPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductListDataImport\ProductListDataImportConfig;

/**
 * @method \Spryker\Zed\ProductListDataImport\Business\ProductListDataImportFacadeInterface getFacade()
 */
class ProductListDataImportPlugin extends AbstractPlugin implements DataImportPluginInterface
{
    /**
     * {@inheritdoc}
     *
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    public function import(?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null): DataImporterReportTransfer
    {
        return $this->getFacade()->importProductList($dataImporterConfigurationTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getImportType(): string
    {
        return ProductListDataImportConfig::IMPORT_TYPE_PRODUCT_LIST;
    }
}
